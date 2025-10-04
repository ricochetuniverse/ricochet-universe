import {useState} from 'preact/hooks';

export default function useErrorDetails() {
    // eslint-disable-next-line @eslint-react/naming-convention/use-state
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
