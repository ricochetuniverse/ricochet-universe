import {Inflate, Z_DATA_ERROR, ZStream} from 'pako';

import TextDecoder from '../helpers/TextDecoder';

export type InflateResult = {
    raw: Uint8Array<ArrayBuffer> | null;
    utf8: string;
    image: Uint8Array<ArrayBuffer> | null;
};

const MAGIC_SKIP_NUMBER = 9;
const JFIF_HEADER = 'ffd8ffe000104a4649460001';

export function inflateFile(buffer: ArrayBuffer): InflateResult {
    const compressed = new Uint8Array(buffer, MAGIC_SKIP_NUMBER);

    let raw: Uint8Array<ArrayBuffer> | null = null;
    let maybeImage: Uint8Array<ArrayBuffer> | null = null;

    // basically what inflate() from pako does behind the scenes
    const inflator = new Inflate();
    let inflatorStream: ZStream | undefined;
    inflator.onStart = (strm) => {
        inflatorStream = strm;
    };
    inflator.push(compressed, true);

    if (inflator.err) {
        if (inflator.err !== Z_DATA_ERROR) {
            throw new Error(inflator.msg);
        }

        // Not a zlib file, try to decode as a Frame
        maybeImage = new Uint8Array(buffer, 13);
    } else if (inflator.result instanceof Uint8Array) {
        raw = inflator.result;

        // If there are any leftover, try to decode as a Sequence
        if (inflatorStream != null && inflatorStream.avail_in > 0) {
            maybeImage = new Uint8Array(
                buffer,
                MAGIC_SKIP_NUMBER + inflatorStream.total_in + 5
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

function maybeJfifFile(image: Uint8Array<ArrayBuffer>) {
    if (checkForJfifHeader(image)) {
        return image;
    }

    return null;
}
