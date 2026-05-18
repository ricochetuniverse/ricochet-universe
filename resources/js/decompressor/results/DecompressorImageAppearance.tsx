import Form from 'react-bootstrap/Form';

import type {Appearance} from './DecompressorResultsImage';

type Props = Readonly<{
    onChange: (appearance: Appearance) => void;
    value: Appearance;
}>;

export default function DecompressorImageAppearance(props: Props) {
    return (
        <Form.Group
            className="d-flex align-items-center"
            controlId="decompressor-appearance"
        >
            <Form.Label className="m-0 me-2">Appearance:</Form.Label>

            <Form.Select
                className="w-auto"
                onChange={(ev) => {
                    const value = ev.currentTarget.value;
                    if (
                        value === 'BLACK' ||
                        value === 'WHITE' ||
                        value === 'CHECKERBOARD'
                    ) {
                        props.onChange(value);
                    }
                }}
                value={props.value}
            >
                <option value="BLACK">Black</option>
                <option value="WHITE">White</option>
                <option value="CHECKERBOARD">Checkerboard</option>
            </Form.Select>
        </Form.Group>
    );
}
