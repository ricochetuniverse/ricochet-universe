import {type ComponentProps} from 'preact';
import {lazy} from 'preact/compat';
import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';

import SuspenseComponent from '../SuspenseComponent';
import SuspenseComponentLoadingText from '../SuspenseComponentLoadingText';

let DecompressorTextEditor = reload();

function reload() {
    return lazy(
        () =>
            import(
                /* webpackChunkName: "decompressor-editor" */ '././DecompressorTextEditor'
            )
    );
}

export default function LoadableDecompressorTextEditor(
    props: ComponentProps<typeof DecompressorTextEditor>
) {
    const [retryTime, setRetryTime] = useState(0);

    return (
        <SuspenseComponent
            fallback={(status) => {
                return <Card.Body>{status}</Card.Body>;
            }}
            loadingText={
                <SuspenseComponentLoadingText text="Loading text viewer..." />
            }
            retry={() => {
                setRetryTime(Date.now());
                DecompressorTextEditor = reload();
            }}
        >
            <DecompressorTextEditor key={retryTime} {...props} />
        </SuspenseComponent>
    );
}
