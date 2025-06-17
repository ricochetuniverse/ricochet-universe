import Button from 'react-bootstrap/Button';

type Props = Readonly<{
    blobUrl: string;
    fileName: string;
}>;

export default function DownloadButton(props: Props) {
    return (
        <Button
            as="a"
            href={props.blobUrl}
            download={props.fileName}
            variant="outline-primary"
        >
            Download
        </Button>
    );
}
