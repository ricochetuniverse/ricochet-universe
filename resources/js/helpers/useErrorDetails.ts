import {useState} from 'preact/hooks';

export default function useErrorDetails() {
    return useState<
        | {
              isError: false;
          }
        | {
              isError: true;
              details: Error | null;
          }
    >({isError: false});
}
