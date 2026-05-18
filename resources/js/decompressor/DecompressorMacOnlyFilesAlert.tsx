import {useMemo} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';

const MAC_ONLY_FILES = /Effects\/DownKey/;

type Props = Readonly<{
    textResult: string;
}>;

export default function DecompressorMacOnlyFilesAlert({textResult}: Props) {
    const matched = useMemo(() => {
        return textResult.match(MAC_ONLY_FILES);
    }, [textResult]);

    if (!matched) {
        return null;
    }

    return (
        <Alert variant="warning">
            This level set requires files that are only available on the Mac
            edition, it cannot be opened on the Windows edition.
        </Alert>
    );
}
