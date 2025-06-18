import {useState} from 'preact/hooks';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import ButtonToolbar from 'react-bootstrap/ButtonToolbar';
import Form from 'react-bootstrap/Form';

function Image(props: {
    appearance: string;
    base64: string;
    className?: string;
}) {
    return (
        <img
            src={'data:image/png;base64,' + props.base64}
            alt=""
            className={
                'decompressor__image--' +
                props.appearance +
                ' ' +
                (props.className ?? '')
            }
        />
    );
}

type Props = Readonly<{
    decodedImages: string[];
}>;

export default function DecompressorResultsNuVelocityDecoded({
    decodedImages,
}: Props) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [showAll, setShowAll] = useState(false);
    const [appearance, setAppearance] = useState<string>('checkerboard');

    return decodedImages.length > 1 ? (
        <>
            <Form.Group className="mb-3">
                <Form.Check
                    type="checkbox"
                    id="decompressor-showAll"
                    checked={showAll}
                    onChange={(ev) => {
                        setShowAll(ev.currentTarget.checked);
                    }}
                    label={`Show all ${decodedImages.length} images`}
                />
            </Form.Group>

            <Form.Group
                className="mb-3 d-flex align-items-center"
                controlId="decompressor-appearance"
            >
                <Form.Label className="m-0 me-2">Appearance:</Form.Label>

                <Form.Select
                    className="w-auto"
                    onChange={(ev) => {
                        setAppearance(ev.currentTarget.value);
                    }}
                    value={appearance}
                >
                    <option value="black">Black</option>
                    <option value="white">White</option>
                    <option value="checkerboard">Checkerboard</option>
                </Form.Select>
            </Form.Group>

            {!showAll ? (
                <>
                    <Image
                        appearance={appearance}
                        base64={decodedImages[currentIndex]}
                        className="mb-3"
                    />

                    <ButtonToolbar className="align-items-center">
                        <ButtonGroup className="me-2">
                            <Button
                                disabled={currentIndex === 0}
                                onClick={() => {
                                    setCurrentIndex(0);
                                }}
                                title="First"
                                variant="outline-primary"
                            >
                                «
                            </Button>

                            <Button
                                disabled={currentIndex === 0}
                                onClick={() => {
                                    setCurrentIndex(currentIndex - 1);
                                }}
                                title="Previous"
                                variant="outline-primary"
                            >
                                &lsaquo;
                            </Button>
                        </ButtonGroup>

                        <Form.Control
                            type="number"
                            className="w-auto me-2"
                            value={currentIndex + 1}
                            min="1"
                            max={decodedImages.length}
                            onChange={(ev) => {
                                const number = parseInt(
                                    ev.currentTarget.value,
                                    10
                                );
                                if (
                                    number >= 1 &&
                                    number <= decodedImages.length
                                ) {
                                    setCurrentIndex(number - 1);
                                }
                            }}
                        />
                        <span className="me-2">of {decodedImages.length}</span>

                        <ButtonGroup>
                            <Button
                                disabled={
                                    currentIndex === decodedImages.length - 1
                                }
                                onClick={() => {
                                    setCurrentIndex(currentIndex + 1);
                                }}
                                title="Next"
                                variant="outline-primary"
                            >
                                &rsaquo;
                            </Button>

                            <Button
                                disabled={
                                    currentIndex === decodedImages.length - 1
                                }
                                onClick={() => {
                                    setCurrentIndex(decodedImages.length - 1);
                                }}
                                title="Last"
                                variant="outline-primary"
                            >
                                »
                            </Button>
                        </ButtonGroup>
                    </ButtonToolbar>
                </>
            ) : (
                <div>
                    {decodedImages.map((img, index) => {
                        return (
                            <Image
                                appearance={appearance}
                                base64={img}
                                className="me-2 mb-2"
                                // eslint-disable-next-line @eslint-react/no-array-index-key
                                key={index}
                            />
                        );
                    })}
                </div>
            )}
        </>
    ) : (
        <Image appearance={appearance} base64={decodedImages[0]} />
    );
}
