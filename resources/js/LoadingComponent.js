// noinspection ES6UnusedImports
import {h} from 'preact';

export default function({error, timedOut, retry, text = 'Loading...'}) {
    if (error) {
        return (
            <div className="d-flex align-items-center">
                Failed to load
                <button
                    className="btn btn-outline-primary ml-3"
                    onClick={retry}
                >
                    Retry
                </button>
            </div>
        );
    }

    if (timedOut) {
        return (
            <div className="d-flex align-items-center">
                This is loading longer than expected... You can continue to wait
                or
                <button
                    className="btn btn-outline-primary ml-3"
                    onClick={retry}
                >
                    Retry
                </button>
            </div>
        );
    }

    return text;
}
