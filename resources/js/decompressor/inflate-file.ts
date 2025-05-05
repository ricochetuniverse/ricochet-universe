import {Inflate} from 'pako/lib/inflate';

import TextDecoder from '../helpers/TextDecoder';

export type InflateResult = {
    raw: Uint8Array | null;
    utf8: string;
    image: Uint8Array | null;
};

const MAGIC_SKIP_NUMBER = 9;
const JFIF_HEADER = 'ffd8ffe000104a4649460001';

export function inflateFile(buffer: ArrayBuffer): InflateResult {
    const compressed = new Uint8Array(buffer, MAGIC_SKIP_NUMBER);

    let raw: Uint8Array | null = null;
    let maybeImage = null;

    // basically what inflate() from pako does behind the scenes
    const inflator = new Inflate();
    inflator.push(compressed, true);

    const Z_DATA_ERROR = -3;
    if (inflator.err) {
        if (inflator.err !== Z_DATA_ERROR) {
            throw new Error(inflator.msg);
        }

        // Not a zlib file, try to decode as a Frame
        maybeImage = new Uint8Array(buffer, 13);
    } else {
        raw = inflator.result as Uint8Array;

        // If there are any leftover, try to decode as a Sequence
        // @ts-expect-error reading field that's not on the TS type
        if (inflator.strm.avail_in > 0) {
            maybeImage = new Uint8Array(
                buffer,
                // @ts-expect-error reading field that's not on the TS type
                MAGIC_SKIP_NUMBER + inflator.strm.total_in + 5
            );
        }
    }

    return {
        raw,
        utf8: raw ? decodeFromUint8Array(raw) : '',
        image: maybeImage ? maybeJfifFile(maybeImage) : null,
    };
}

export function decodeFromUint8Array(text: Uint8Array): string {
    return new TextDecoder('windows-1252', {
        fatal: true,
    }).decode(text);
}

function checkForJfifHeader(raw: Uint8Array) {
    const strip = raw.subarray(0, JFIF_HEADER.length / 2);

    let header = '';
    for (let i = 0; i < strip.length; i += 1) {
        header += strip[i].toString(16).padStart(2, '0');
    }

    return header === JFIF_HEADER;
}

function maybeJfifFile(image: Uint8Array) {
    if (checkForJfifHeader(image)) {
        return image;
    }

    return null;
}
