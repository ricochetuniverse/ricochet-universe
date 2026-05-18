import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';
import Col from 'react-bootstrap/Col';
import Row from 'react-bootstrap/Row';
import Stack from 'react-bootstrap/Stack';

import DecompressorModRequirementAlert from '../DecompressorModRequirementAlert';
import type {
    DecompressorBlobUrls,
    DecompressorResultJs,
} from '../DecompressorTypes';
import DownloadButton from '../DownloadButton';
import LoadableDecompressorTextEditor from '../LoadableDecompressorTextEditor';

import DecompressorImageAppearance from './DecompressorImageAppearance';
import DecompressorResultsImage, {
    type Appearance,
} from './DecompressorResultsImage';

type Props = Readonly<{
    blobUrls: DecompressorBlobUrls;
    enableBrowserTextEditor: boolean;
    fileName: string;
    result: DecompressorResultJs;
}>;

export default function DecompressorResultsJs(props: Props) {
    const [appearance, setAppearance] = useState<Appearance>('CHECKERBOARD');

    return (
        <>
            <DecompressorModRequirementAlert
                textResult={props.result.text ?? ''}
            />

            {props.blobUrls.image != null ? (
                <Card as="section">
                    <Card.Header as="h2">Decompressed image</Card.Header>

                    <Card.Body>
                        <Stack gap={3}>
                            <div>
                                <DownloadButton
                                    blobUrl={props.blobUrls.image}
                                    fileName={
                                        props.fileName.replace(
                                            /\.Sequence$/,
                                            ''
                                        ) + '.jpg'
                                    }
                                />
                            </div>

                            <DecompressorImageAppearance
                                onChange={setAppearance}
                                value={appearance}
                            />
                        </Stack>
                    </Card.Body>

                    <div className="overflow-x-auto">
                        <DecompressorResultsImage
                            appearance={appearance}
                            src={props.blobUrls.image}
                        />
                    </div>
                </Card>
            ) : null}

            {props.result.text ? (
                <Card as="section">
                    <Card.Header as="h2">
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
                                                /\.Ricochet(?:I|LW)$/,
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
