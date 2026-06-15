export const ImageType = {
    SEQUENCE: 'SEQUENCE',
    FRAME: 'FRAME',
} as const;

export type ImageTypeEnum = (typeof ImageType)[keyof typeof ImageType];
