import type {ImageTypeEnum} from './ImageType';

export type WorkerRequest = {
    messageId: number;
    loaderUrl: string;
    imageType: ImageTypeEnum;
    bytes: Uint8Array<ArrayBuffer>;
};

export type WorkerStatuses = 'LOADING' | 'PROCESSING' | 'FINISHED' | 'ERROR';

export type WorkerResponses =
    | {
          status: Exclude<WorkerStatuses, 'FINISHED' | 'ERROR'>;
      }
    | {
          status: 'FINISHED';
          decodedImagesJson: string;
      }
    | {
          status: 'ERROR';
          errorDetails: string;
      };
