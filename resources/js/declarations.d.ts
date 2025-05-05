/* eslint-disable no-unused-vars */

declare module '*.css';

// todo maybe switch to pako module?
declare module 'pako/lib/inflate' {
    import {Data, FlushValues, Header, InflateOptions, ReturnCodes} from 'pako';

    export class Inflate {
        constructor(options?: InflateOptions);
        header?: Header | undefined;
        err: ReturnCodes;
        msg: string;
        result: Uint8Array | string;
        onData(chunk: Data): void;
        onEnd(status: number): void;
        push(data: Data, mode?: FlushValues | boolean): boolean;
    }
}

declare module 'uppie' {
    function uppie(
        node: Node | Node[] | NodeList,
        callback: (event: Event, formData: FormData, files: File[]) => void
    ): void;

    function uppie(
        node: Node | Node[] | NodeList,
        opts: {
            name?: string;
        },
        callback: (event: Event, formData: FormData, files: File[]) => void
    ): void;
}
