export type DecompressorResultJs = {
    unpacker: 'JS';
    text?: string;
    image?: Uint8Array;
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
