import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

import LoadableDecompressorTextEditor from '../../monaco/LoadableDecompressorTextEditor';
import DecompressorMacOnlyFilesAlert from '../DecompressorMacOnlyFilesAlert';
import DecompressorModRequirementAlert from '../DecompressorModRequirementAlert';
import type {
    DecompressorBlobUrls,
    DecompressorResultJs,
} from '../DecompressorTypes';
import DownloadButton from '../DownloadButton';
import {useSettingsStore} from '../settings-store';

import DecompressorImageAppearance from './DecompressorImageAppearance';
import DecompressorResultsImage from './DecompressorResultsImage';
import DecompressorResultsLevelSetParser from './levelSet/DecompressorResultsLevelSetParser';

type Props = Readonly<{
    blobUrls: DecompressorBlobUrls;
    fileName: string;
    result: DecompressorResultJs;
}>;

export default function DecompressorResultsJs(props: Props) {
    const imageAppearance = useSettingsStore((state) => state.image.appearance);
    const setImageAppearance = useSettingsStore(
        (state) => state.image.setAppearance
    );

    const showTextEditor = useSettingsStore((state) => state.text.show);
    const setShowTextEditor = useSettingsStore((state) => state.text.setShow);

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
                                onChange={setImageAppearance}
                                value={imageAppearance}
                            />
                        </Stack>
                    </Card.Body>

                    <div className="overflow-x-auto">
                        <DecompressorResultsImage
                            appearance={imageAppearance}
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
                        <Stack gap={3}>
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
                                        If you’re manually editing this file
                                        with a text editor, be sure to save the
                                        file with Windows (CRLF) line endings
                                        and Windows-1252 text encoding to ensure
                                        game compatibility.
                                    </p>
                                ) : null}
                            </Stack>

                            {!showTextEditor ? (
                                <div>
                                    <Button
                                        variant="outline-primary"
                                        onClick={() => {
                                            setShowTextEditor(true);
                                        }}
                                    >
                                        View text in browser
                                    </Button>
                                </div>
                            ) : null}
                        </Stack>
                    </Card.Body>

                    {showTextEditor ? (
                        <LoadableDecompressorTextEditor
                            text={props.result.text}
                        />
                    ) : null}
                </Card>
            ) : null}
        </>
    );
}
