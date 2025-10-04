declare module '*.css';
declare module '*.scss';

// todo maybe switch to pako module?
declare module 'pako/lib/inflate' {
    import {
        Data,
        FlushValues,
        Header,
        InflateOptions,
        ReturnCodes,
        Uint8ArrayReturnType,
    } from 'pako';

    export class Inflate {
        constructor(options?: InflateOptions);
        header?: Header | undefined;
        err: ReturnCodes;
        msg: string;
        result: Uint8ArrayReturnType | string;
        onData(chunk: Data): void;
        onEnd(status: number): void;
        push(data: Data, mode?: FlushValues | boolean): boolean;
    }
}
