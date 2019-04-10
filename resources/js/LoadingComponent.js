import {h} from 'preact';
import {Button} from 'reactstrap';

export default function({error, timedOut, retry, text = 'Loading...'}) {
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
