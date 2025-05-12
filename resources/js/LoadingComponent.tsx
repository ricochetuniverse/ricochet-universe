import Button from 'react-bootstrap/Button';

type Props = Readonly<
    LoadableExport.LoadingComponentProps & {
        text?: string;
    }
>;

export default function LoadingComponent({
    error,
    timedOut,
    retry,
    text = 'Loading...',
}: Props) {
    if (error) {
        return (
            <div className="d-flex align-items-center">
                Failed to load
                <Button
                    variant="outline-primary"
                    className="ms-3"
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
                    variant="outline-primary"
                    className="ms-3"
                    onClick={retry}
                >
                    Retry
                </Button>
            </div>
        );
    }

    return text;
}
