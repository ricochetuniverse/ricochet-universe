declare module '*.css' {
    const styles: {[className: string]: string};
    export default styles;
}

declare module '*.scss' {
    const styles: {[className: string]: string};
    export default styles;
}

declare module '*.jpg';
declare module '*.gif';
declare module '*.png';

// pako
declare module 'pako/lib/zlib/constants' {
    import {constants} from 'pako';
    export = constants;
}

declare module 'pako/lib/zlib/zstream' {
    export default class ZStream {
        input: Uint8Array;
        next_in: number;
        avail_in: number;
        total_in: number;
        output: Uint8Array;
        next_out: number;
        avail_out: number;
        total_out: number;
        msg: string;
        state: unknown; // InflateState | DeflateState
        data_type: 2; // constants.Z_UNKNOWN
        adler: number;
    }
}

declare module 'pako/lib/inflate' {
    import {Inflate} from 'pako';
    import ZStream from 'pako/lib/zlib/zstream';

    class InflateExtended extends Inflate {
        strm: ZStream;
    }
    export {InflateExtended as Inflate};
}
