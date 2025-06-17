import Card from 'react-bootstrap/Card';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';

import type {DecompressorBlobUrls} from './DecompressorBlobUrlsType';
import DecompressorModRequirementAlert from './DecompressorModRequirementAlert';
import DownloadButton from './DownloadButton';
import type {InflateResult} from './inflate-file';
import LoadableDecompressorEditor from './LoadableDecompressorEditor';

type Props = Readonly<{
    blobUrls: DecompressorBlobUrls;
    enableBrowserTextEditor: boolean;
    fileName: string;
    result: InflateResult;
}>;

export default function DecompressorResults(props: Props) {
    return (
        <>
            <DecompressorModRequirementAlert textResult={props.result.utf8} />

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
                            className="decompressor__image"
                        />
                    </div>
                </Card>
            ) : null}

            {props.result.utf8 ? (
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
                        <LoadableDecompressorEditor text={props.result.utf8} />
                    ) : null}
                </Card>
            ) : null}
        </>
    );
}
