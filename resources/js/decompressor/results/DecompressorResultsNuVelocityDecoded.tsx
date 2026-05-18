import {useState} from 'preact/hooks';
import Button from 'react-bootstrap/Button';
import ButtonGroup from 'react-bootstrap/ButtonGroup';
import ButtonToolbar from 'react-bootstrap/ButtonToolbar';
import Form from 'react-bootstrap/Form';
import Stack from 'react-bootstrap/Stack';

import DecompressorImageAppearance from './DecompressorImageAppearance';
import DecompressorResultsImage, {
    type Appearance,
} from './DecompressorResultsImage';

const BASE64_DATA_URI = 'data:image/png;base64,';

type Props = Readonly<{
    decodedImages: string[];
}>;

export default function DecompressorResultsNuVelocityDecoded({
    decodedImages,
}: Props) {
    const [currentIndex, setCurrentIndex] = useState(0);
    const [showAll, setShowAll] = useState(false);
    const [appearance, setAppearance] = useState<Appearance>('CHECKERBOARD');

    return decodedImages.length > 1 ? (
        <Stack gap={3}>
            <Form.Check
                type="checkbox"
                id="decompressor-showAll"
                checked={showAll}
                onChange={(ev) => {
                    setShowAll(ev.currentTarget.checked);
                }}
                label={`Show all ${decodedImages.length.toString()} images`}
            />

            <DecompressorImageAppearance
                onChange={setAppearance}
                value={appearance}
            />

            {!showAll ? (
                <>
                    <div>
                        <DecompressorResultsImage
                            appearance={appearance}
                            src={BASE64_DATA_URI + decodedImages[currentIndex]}
                        />
                    </div>

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
                            <DecompressorResultsImage
                                appearance={appearance}
                                className="me-2 mb-2"
                                src={BASE64_DATA_URI + img}
                                // eslint-disable-next-line @eslint-react/no-array-index-key
                                key={index}
                            />
                        );
                    })}
                </div>
            )}
        </Stack>
    ) : (
        <DecompressorResultsImage
            appearance={appearance}
            src={BASE64_DATA_URI + decodedImages[0]}
        />
    );
}
