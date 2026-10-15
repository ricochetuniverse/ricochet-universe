import nullthrows from 'nullthrows';
import {useCallback, useState} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';
import Button from 'react-bootstrap/Button';
import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

import CustomFileInput from '../CustomFileInput';

type Pixel = {
    x: number;
    y: number;
    width: number;
    r: number;
    g: number;
    b: number;
    a: number;
};

function getScrapBookData(pixels: Pixel[]) {
    const scale = 1;

    return `CScrapBookData
{
\tScraps=Array
\t{
\t\tScrap=CSelectedObjects
\t\t{
\t\t\tObjects=Array
\t\t\t{
\t\t\t\tObject=CSelectedObjectInfo
\t\t\t\t{
\t\t\t\t\tSelected Object=CDecorationGroup
\t\t\t\t\t{
\t\t\t\t\t\tPosition=0,0
\t\t\t\t\t\tBlur Radius=0
\t\t\t\t\t\tDecoration Group=Array
\t\t\t\t\t\t{
\t\t\t\t\t\t\tItem Count=${pixels.length.toString()}
${pixels
    .map(
        (pixel) => `\t\t\t\t\t\t\tGrouped Decoration=CDecorationShape
\t\t\t\t\t\t\t{
\t\t\t\t\t\t\t\tPosition=${(pixel.x * scale).toString()},${(pixel.y * scale).toString()}
\t\t\t\t\t\t\t\tShape=[ ] :M ${scale.toString()}, -${scale.toString()} :L -${scale.toString()}, -${scale.toString()} :L ${(
            scale * pixel.width
        ).toString()}, -${scale.toString()} :L ${(scale * pixel.width).toString()}, ${scale.toString()} :L -${scale.toString()}, ${scale.toString()}
\t\t\t\t\t\t\t\tFill Color=${pixel.r.toString()},${pixel.g.toString()},${pixel.b.toString()},${pixel.a.toString()}
\t\t\t\t\t\t\t\tOutline Color=0,0,0,0
\t\t\t\t\t\t\t\tOutline Width=0
\t\t\t\t\t\t\t}
`
    )
    .join('')}
\t\t\t\t\tLayer Object Is On=Background Layer
\t\t\t\t}
\t\t\t}
\t\t}
\t}
}`;
}

function generateBlobUrl(raw: string): string {
    const blob = new Blob([raw], {type: 'text/plain'});
    return window.URL.createObjectURL(blob);
}

export default function ImageToCanvasApp() {
    const [error, setError] = useState<string | null>(null);
    const [loading, setLoading] = useState(false);

    const [resultBlobUrl, setResultBlobUrl] = useState('');

    const onFileChange = useCallback((ev: Event) => {
        setError(null);
        setLoading(true);
        setResultBlobUrl('');

        const fileInput = ev.currentTarget;
        if (!(fileInput instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }

        const files = fileInput.files;
        if (!files || files.length === 0) {
            return;
        }

        const file = files[0];

        // playfield max size is 616x589
        const canvas = document.createElement('canvas');

        const img = new Image();
        img.onload = () => {
            canvas.width = img.width;
            canvas.height = img.height;

            const canvasContext = nullthrows(canvas.getContext('2d'));
            canvasContext.clearRect(0, 0, canvas.width, canvas.height);
            canvasContext.drawImage(img, 0, 0);

            const pixels: Pixel[] = [];
            for (let y = 0; y < img.height; y += 1) {
                for (let x = 0; x < img.width; x += 1) {
                    const firstPixel = canvasContext.getImageData(
                        x,
                        y,
                        1,
                        1
                    ).data;

                    // fully transparent, don't bother
                    if (
                        firstPixel[0] === 0 &&
                        firstPixel[1] === 0 &&
                        firstPixel[2] === 0 &&
                        firstPixel[3] === 0
                    ) {
                        continue;
                    }

                    // scan the next horizontal pixel
                    let secondPixel;
                    let scannedWidth = 0;
                    do {
                        scannedWidth += 1;
                        secondPixel = canvasContext.getImageData(
                            x + scannedWidth + 1,
                            y,
                            1,
                            1
                        ).data;
                    } while (
                        firstPixel[0] === secondPixel[0] &&
                        firstPixel[1] === secondPixel[1] &&
                        firstPixel[2] === secondPixel[2] &&
                        firstPixel[3] === secondPixel[3] &&
                        x + scannedWidth < img.width
                    );

                    pixels.push({
                        x,
                        y,
                        width: scannedWidth,
                        r: firstPixel[0],
                        g: firstPixel[1],
                        b: firstPixel[2],
                        a: firstPixel[3],
                    });

                    x += scannedWidth - 1;
                }
            }
            setResultBlobUrl(generateBlobUrl(getScrapBookData(pixels)));
            setLoading(false);

            window.URL.revokeObjectURL(img.src);
        };
        img.onerror = () => {
            setLoading(false);
            setError('There was an error loading the image.');

            window.URL.revokeObjectURL(img.src);
        };
        img.src = window.URL.createObjectURL(file);
    }, []);

    return (
        <Stack gap={3}>
            <Card as="section">
                <Card.Header as="h1">Image to canvas</Card.Header>

                <Card.Body>
                    <CustomFileInput
                        accept=".jpg,.jpeg,.gif,.png"
                        onChange={onFileChange}
                    />
                </Card.Body>
            </Card>

            {loading ? <Alert variant="info">Loading...</Alert> : null}

            {error ? <Alert variant="danger">{error}</Alert> : null}

            {resultBlobUrl !== '' ? (
                <Card as="section">
                    <Card.Header as="h2">Scrapbook result</Card.Header>

                    <Card.Body>
                        <Button
                            as="a"
                            href={resultBlobUrl}
                            download="Scrap Book.object.txt"
                            variant="outline-primary"
                        >
                            Download
                        </Button>
                    </Card.Body>
                </Card>
            ) : null}
        </Stack>
    );
}
