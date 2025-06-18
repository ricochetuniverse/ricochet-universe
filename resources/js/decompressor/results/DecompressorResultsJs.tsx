import Card from 'react-bootstrap/Card';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';

import DecompressorModRequirementAlert from '../DecompressorModRequirementAlert';
import type {
    DecompressorBlobUrls,
    DecompressorResultJs,
} from '../DecompressorTypes';
import DownloadButton from '../DownloadButton';
import LoadableDecompressorTextEditor from '../LoadableDecompressorTextEditor';

type Props = Readonly<{
    blobUrls: DecompressorBlobUrls;
    enableBrowserTextEditor: boolean;
    fileName: string;
    result: DecompressorResultJs;
}>;

export default function DecompressorResultsJs(props: Props) {
    return (
        <>
            <DecompressorModRequirementAlert
                textResult={props.result.text ?? ''}
            />

            {props.blobUrls.image != null ? (
                <Card className="mb-3">
                    <Card.Header>Decompressed image</Card.Header>

                    <Card.Body>
                        <DownloadButton
                            blobUrl={props.blobUrls.image}
                            fileName={
                                props.fileName.replace(/\.Sequence$/, '') +
                                '.jpg'
                            }
                        />
                    </Card.Body>

                    <div className="overflow-x-auto">
                        <img
                            src={props.blobUrls.image}
                            alt="Decompressed result"
                            className="decompressor__image decompressor__image--checkerboard"
                        />
                    </div>
                </Card>
            ) : null}

            {props.result.text ? (
                <Card className="mb-3">
                    <Card.Header>
                        {props.blobUrls.image == null
                            ? 'Decompressed text'
                            : 'Image metadata'}
                    </Card.Header>

                    <Card.Body>
                        <Row className="align-items-center">
                            {props.blobUrls.text != null ? (
                                <Col xs="auto">
                                    <DownloadButton
                                        blobUrl={props.blobUrls.text}
                                        fileName={
                                            props.fileName.replace(
                                                /\.Ricochet(I|LW)$/,
                                                ''
                                            ) + ' (decompressed).txt'
                                        }
                                    />
                                </Col>
                            ) : null}

                            {!props.blobUrls.image ? (
                                <Col>
                                    If you’re manually editing this file with a
                                    text editor, be sure to save the file with
                                    Windows (CRLF) line endings and Windows-1252
                                    text encoding to ensure game compatibility.
                                </Col>
                            ) : null}
                        </Row>
                    </Card.Body>

                    {props.enableBrowserTextEditor ? (
                        <LoadableDecompressorTextEditor
                            text={props.result.text}
                        />
                    ) : null}
                </Card>
            ) : null}
        </>
    );
}
