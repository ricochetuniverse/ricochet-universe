// Based on https://github.com/dotnet/blazor-samples/blob/main/10.0/DotNetOnWebWorkersReact/react/src/

import type {ImageTypeEnum} from './ImageType';
import type {WorkerResponses} from './WorkerMessageTypes';

const messagePorts = new Map<number, (response: WorkerResponses) => void>();
let messageNewId = 0;

let worker: Worker | null = null;

function startWorker() {
    if (worker) {
        return worker;
    }

    worker = new Worker(new URL('./worker-background', import.meta.url), {
        name: 'nuvelocity-worker', // this filename has a specific CSP on Caddyfile
        type: 'module',
    });

    worker.addEventListener(
        'message',
        ({
            data: response,
        }: MessageEvent<{
            messageId: number;
            result: WorkerResponses;
        }>) => {
            const port = messagePorts.get(response.messageId);
            if (!port) {
                return;
            }
            port(response.result);

            if (
                response.result.status === 'FINISHED' ||
                response.result.status === 'ERROR'
            ) {
                messagePorts.delete(response.messageId);
            }
        },
        false
    );

    return worker;
}

export function unpack(
    dotNetLoaderUrl: string,
    imageType: ImageTypeEnum,
    bytes: Uint8Array,
    onStatusChanged: (response: WorkerResponses) => void
) {
    const worker = startWorker();

    messageNewId += 1;
    messagePorts.set(messageNewId, onStatusChanged);

    worker.postMessage({
        messageId: messageNewId,
        loaderUrl: dotNetLoaderUrl,
        imageType,
        bytes,
    });
}
