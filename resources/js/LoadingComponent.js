// @flow strict

import {h} from 'preact';
import {Button} from 'reactstrap';

type Props = $ReadOnly<{
    // LoadingProps from react-loadable
    isLoading: boolean,
    pastDelay: boolean,
    timedOut: boolean,
    retry: () => void,
    error: ?Error,

    text?: string,
    ...
}>;

export default function ({
    error,
    timedOut,
    retry,
    text = 'Loading...',
}: Props): React.Node {
    if (error) {
        return (
            <div className="d-flex align-items-center">
                Failed to load
                <Button
                    outline
                    color="primary"
                    className="ml-3"
                    onClick={retry}
                >
                    Retry
                </Button>
            </div>
        );
    }

    if (timedOut) {
        return (
            <div className="d-flex align-items-center">
                This is loading longer than expected... You can continue to wait
                or
                <Button
                    outline
                    color="primary"
                    className="ml-3"
                    onClick={retry}
                >
                    Retry
                </Button>
            </div>
        );
    }

    return text;
}
