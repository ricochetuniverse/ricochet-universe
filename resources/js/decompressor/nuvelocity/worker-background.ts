import type {DotNetType} from './DotNetType';
import loadAssembly from './loadAssembly';
import type {WorkerRequest, WorkerResponses} from './WorkerMessageTypes';

let dotNet: DotNetType | null = null;

async function onMessage(request: WorkerRequest) {
    const reply = (response: WorkerResponses) => {
        self.postMessage({
            messageId: request.messageId,
            response,
        });
    };

    try {
        if (!dotNet) {
            reply({status: 'LOADING'});

            dotNet = await loadAssembly(
                request.loaderUrl,
                process.env.NODE_ENV === 'development'
            );
        }

        reply({status: 'PROCESSING'});

        let decodedImagesJson = '';
        switch (request.imageType) {
            case 'SEQUENCE':
                decodedImagesJson = dotNet.exports.Unpacker.ReadSequence(
                    request.bytes
                );
                break;

            case 'FRAME':
                decodedImagesJson = dotNet.exports.Unpacker.ReadFrame(
                    request.bytes
                );
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
