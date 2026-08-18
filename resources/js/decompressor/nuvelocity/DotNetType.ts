export type AppExports = {
    Unpacker: {
        ReadSequence(file: Uint8Array): string;
        ReadFrame(file: Uint8Array): string;
    };
};

export type DotNetType = {
    exports: AppExports;
};
