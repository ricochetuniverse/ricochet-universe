// @flow strict

import {Component, h} from 'preact';
import {Alert, Button, Card, CardBody, CardHeader} from 'reactstrap';

import CustomFileInput from '../CustomFileInput';

type State = $ReadOnly<{|
    fileName: string,
    loading: boolean,
    error: string,
    result: string,
|}>;

type Pixel = {|
    x: number,
    y: number,
    width: number,
    r: number,
    g: number,
    b: number,
    a: number,
|};

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
\t\t\t\t\t\t\tItem Count=${pixels.length}
${pixels
    .map(
        (pixel) => `\t\t\t\t\t\t\tGrouped Decoration=CDecorationShape
\t\t\t\t\t\t\t{
\t\t\t\t\t\t\t\tPosition=${pixel.x * scale},${pixel.y * scale}
\t\t\t\t\t\t\t\tShape=[ ] :M ${scale}, -${scale} :L -${scale}, -${scale} :L ${
            scale * pixel.width
        }, -${scale} :L ${scale * pixel.width}, ${scale} :L -${scale}, ${scale}
\t\t\t\t\t\t\t\tFill Color=${pixel.r},${pixel.g},${pixel.b},${pixel.a}
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

export default class ImageToCanvasApp extends Component<{||}, State> {
    canvas: HTMLCanvasElement = document.createElement('canvas');

    state: State = {
        fileName: '',
        loading: false,
        error: '',
        result: '',
    };

    render(): React.Node {
        return (
            <div className="mb-n3">
                <Card className="mb-3">
                    <CardHeader>Image to canvas</CardHeader>

                    <CardBody>
                        <CustomFileInput
                            label="Select..."
                            accept=".jpg,.jpeg,.gif,.png"
                            onChange={this.onFileChange}
                        />
                    </CardBody>
                </Card>

                {this.state.loading ? (
                    <Alert color="info" fade={false}>
                        Loading...
                    </Alert>
                ) : null}

                {this.state.error ? (
                    <Alert color="danger" fade={false}>
                        {this.state.error}
                    </Alert>
                ) : null}

                {this.state.result ? (
                    <Card className="mb-3">
                        <CardHeader>Scrapbook result</CardHeader>

                        <CardBody>
                            <Button
                                tag="a"
                                href={this.state.result}
                                download="Scrap Book.object.txt"
                                outline
                                color="primary"
                            >
                                Download
                            </Button>
                        </CardBody>
                    </Card>
                ) : null}
            </div>
        );
    }

    onFileChange: (ev: Event) => void = (ev: Event) => {
        this.setState({
            fileName: '',
            loading: true,
            error: '',
            result: '',
        });

        const fileInput = ev.currentTarget;
        if (!(fileInput instanceof HTMLInputElement)) {
            throw new Error('Expected HTMLInputElement');
        }
        if (fileInput.files && fileInput.files[0]) {
            this.processFile(fileInput.files[0]);
        }
    };

    processFile: (file: File) => void = (file: File) => {
        // playfield max size is 616x589

        const img = new Image();
        img.onload = () => {
            this.canvas.width = img.width;
            this.canvas.height = img.height;

            const canvasContext = this.canvas.getContext('2d');
            canvasContext.clearRect(
                0,
                0,
                this.canvas.width,
                this.canvas.height
            );
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
            this.setState({
                loading: false,
                result: generateBlobUrl(getScrapBookData(pixels)),
            });

            URL.revokeObjectURL(img.src);
        };
        img.onerror = () => {
            this.setState({
                loading: false,
                error: 'There was an error loading the image.',
            });
        };
        img.src = URL.createObjectURL(file);
    };
}
