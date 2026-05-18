export type DecompressorResultJs = {
    unpacker: 'JS';
    raw: Uint8Array<ArrayBuffer> | null;
    text: string;
    image: Uint8Array | null;
};

export type DecompressorResultNuVelocity = {
    unpacker: 'NUVELOCITY';
    bytes: Uint8Array;
};

export type DecompressorResult =
    | DecompressorResultJs
    | DecompressorResultNuVelocity;

export type DecompressorBlobUrls = {
    text: string | null;
    image: string | null;
};
