import {useState} from 'preact/hooks';

export default function useErrorDetails() {
    // eslint-disable-next-line @eslint-react/use-state
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
