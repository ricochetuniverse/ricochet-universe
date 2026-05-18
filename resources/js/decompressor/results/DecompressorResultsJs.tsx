import {useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

import DecompressorMacOnlyFilesAlert from '../DecompressorMacOnlyFilesAlert';
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
import DecompressorResultsLevelSetParser from './levelSet/DecompressorResultsLevelSetParser';

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
            {props.result.text !== '' ? (
                <DecompressorModRequirementAlert
                    textResult={props.result.text}
                />
            ) : null}

            {props.result.text !== '' ? (
                <DecompressorMacOnlyFilesAlert textResult={props.result.text} />
            ) : null}

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

            {props.result.raw ? (
                <DecompressorResultsLevelSetParser raw={props.result.raw} />
            ) : null}

            {props.result.text !== '' ? (
                <Card as="section">
                    <Card.Header as="h2">
                        {props.blobUrls.image == null
                            ? 'Decompressed text'
                            : 'Image metadata'}
                    </Card.Header>

                    <Card.Body>
                        <Stack
                            direction="horizontal"
                            gap={3}
                            className="align-items-center"
                        >
                            {props.blobUrls.text != null ? (
                                <DownloadButton
                                    blobUrl={props.blobUrls.text}
                                    fileName={
                                        props.fileName.replace(
                                            /\.Ricochet(?:I|LW)$/,
                                            ''
                                        ) + ' (decompressed).txt'
                                    }
                                />
                            ) : null}

                            {!props.blobUrls.image ? (
                                <p className="m-0">
                                    If you’re manually editing this file with a
                                    text editor, be sure to save the file with
                                    Windows (CRLF) line endings and Windows-1252
                                    text encoding to ensure game compatibility.
                                </p>
                            ) : null}
                        </Stack>
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
