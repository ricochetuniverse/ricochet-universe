import type {WorkerRequest, WorkerResponses} from './WorkerMessageTypes';

type AppExports = {
    Unpacker: {
        ReadSequence(file: Uint8Array): string;
        ReadFrame(file: Uint8Array): string;
    };
};

let dotNet: AppExports | null = null;

async function loadAssembly<AppExports>(loaderUrl: string) {
    const module = (await import(
        /* webpackIgnore: true */ loaderUrl
    )) as typeof import('@ricochetuniverse/nuvelocity-unpacker/dotnet/_framework/dotnet');

    const {getAssemblyExports, getConfig} = await module.dotnet
        .withDiagnosticTracing(process.env.NODE_ENV === 'development')
        .create();

    const {mainAssemblyName} = getConfig();
    if (!mainAssemblyName) {
        throw new Error('Missing main assembly name');
    }

    const exports = (await getAssemblyExports(mainAssemblyName)) as AppExports;
    if (!exports) {
        throw new Error('Missing assembly exports');
    }

    return exports;
}

async function onMessage(request: WorkerRequest) {
    const reply = (result: WorkerResponses) => {
        self.postMessage({
            messageId: request.messageId,
            result,
        });
    };

    try {
        if (!dotNet) {
            reply({status: 'LOADING'});

            dotNet = await loadAssembly(request.loaderUrl);
        }

        reply({status: 'PROCESSING'});

        let decodedImagesJson = '';
        switch (request.imageType) {
            case 'SEQUENCE':
                decodedImagesJson = dotNet.Unpacker.ReadSequence(request.bytes);
                break;

            case 'FRAME':
                decodedImagesJson = dotNet.Unpacker.ReadFrame(request.bytes);
                break;

            default:
                throw new Error('Unsupported image type');
        }

        reply({
            status: 'FINISHED',
            decodedImagesJson,
        });
    } catch (error) {
        reply({
            status: 'ERROR',
            errorDetails:
                error instanceof Error ? error.message : 'Unknown error',
        });
    }
}

self.addEventListener(
    'message',
    (ev: MessageEvent<WorkerRequest>) => {
        void onMessage(ev.data);
    },
    false
);
