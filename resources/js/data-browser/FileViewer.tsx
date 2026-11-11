import {useEffect, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Stack from 'react-bootstrap/Stack';

import DownloadButton from '../decompressor/DownloadButton';
import LoadableDecompressorTextEditor from '../monaco/LoadableDecompressorTextEditor';

function getFileExtension(filename: string): string {
    const filenameSplit = filename.split('.');
    return filenameSplit[filenameSplit.length - 1].toLowerCase();
}

/*

File extensions:

- Text: txt, foo, BrickStyleSheet, PGen, Background, JWindowSkin, modinfo, modDB, PowerUp, Ricochet, RicochetI
- Image: Frame, Sequence
- Audio: ogg, wav
- Other: swf, ttf, Thumbs.db

*/
type FileHandler = 'TEXT' | 'IMAGE' | 'AUDIO' | 'RAW';

function getFileHandler(fileExtension: string): FileHandler {
    switch (fileExtension) {
        case 'frame':
        case 'sequence':
            return 'IMAGE';

        case 'ogg':
        case 'wav':
            return 'AUDIO';

        case 'swf':
        case 'ttf':
        case 'db':
            return 'RAW';

        default:
            return 'TEXT';
    }
}

async function processFile(fileHandler: FileHandler, response: Response) {
    switch (fileHandler) {
        case 'TEXT':
            return response.text();

        default:
            return response.bytes();
    }
}

type Props = Readonly<{
    storageUrl: string;
    dirname: string;
    filename: string;
}>;

export default function FileViewer({storageUrl, dirname, filename}: Props) {
    const fileUrl = storageUrl + (dirname !== '/' ? dirname : '') + filename;
    const fileHandler = getFileHandler(getFileExtension(filename));

    const [fileContents, setFileContents] = useState<
        string | Uint8Array | null
    >(null);
    const [loading, setLoading] = useState(true);
    const [errorDetails, setErrorDetails] = useState<Error | null>(null);

    useEffect(() => {
        if (fileHandler === 'AUDIO' || fileHandler === 'RAW') {
            return;
        }

        const abortController = new AbortController();

        async function fetchContents() {
            setFileContents(null);
            setLoading(true);
            setErrorDetails(null);

            try {
                const response = await fetch(fileUrl, {
                    signal: abortController.signal,
                });
                if (!response.ok) {
                    throw new Error(
                        `Failed to load file preview (response status: ${response.status.toString()})`
                    );
                }

                setFileContents(await processFile(fileHandler, response));
                setLoading(false);
            } catch (error) {
                console.error(error);
                setErrorDetails(
                    error instanceof Error
                        ? error
                        : new Error(
                              error?.toString() ??
                                  'There was a problem loading the file preview.'
                          )
                );
            }
        }

        void fetchContents();
        return () => {
            abortController.abort();
        };
    }, [fileHandler, fileUrl]);

    return (
        <Stack gap={3}>
            <div>
                <DownloadButton blobUrl={fileUrl} fileName={filename} />
            </div>

            {errorDetails != null ? (
                <Alert variant="danger">{errorDetails.message}</Alert>
            ) : loading ? (
                'Loading file preview...'
            ) : fileHandler === 'TEXT' && typeof fileContents === 'string' ? (
                <LoadableDecompressorTextEditor text={fileContents} />
            ) : fileHandler === 'IMAGE' ? (
                'todo IMAGE'
            ) : fileHandler === 'AUDIO' ? (
                // eslint-disable-next-line jsx-a11y-x/media-has-caption
                <audio src={fileUrl} controls autoplay className="w-100" />
            ) : (
                // RAW
                <Alert variant="info">
                    No file preview is available, download the file instead.
                </Alert>
            )}
        </Stack>
    );
}
