// @flow

import {Inflate} from 'pako/lib/inflate';
import TextDecoder from '../helpers/TextDecoder';

export type InflateResult = {|
    raw: Uint8Array,
    utf8: string,
    image: ?Uint8Array,
|};

const MAGIC_SKIP_NUMBER = 9;

export function inflateFile(buffer: ArrayBuffer): InflateResult {
    const compressed = new Uint8Array(buffer, MAGIC_SKIP_NUMBER);

    // basically what inflate() from pako does behind the scenes
    const inflator = new Inflate();
    inflator.push(compressed, true);
    const raw = inflator.result;

    // If there are any leftover, try to decode it as a JPEG sequence
    let image = null;
    if (inflator.strm.avail_in > 0) {
        image = new Uint8Array(
            buffer,
            MAGIC_SKIP_NUMBER +
                inflator.strm.input.length -
                inflator.strm.avail_in +
                5
        );
    }

    return {
        raw,
        utf8: decodeFromUint8Array(raw),
        image,
    };
}

export function decodeFromUint8Array(text: Uint8Array) {
    return new TextDecoder('windows-1252', {
        fatal: true,
    }).decode(text);
}
