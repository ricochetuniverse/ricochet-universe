import {useEffect, useState} from 'preact/hooks';
import Button from 'react-bootstrap/Button';
import Col from 'react-bootstrap/Col';
import Modal from 'react-bootstrap/Modal';
import Row from 'react-bootstrap/Row';
import type {z} from 'zod';

import {RoundInfoSchema} from './RoundInfoType';

type Props = Readonly<{
    launchTime?: number;
    roundInfo: z.infer<typeof RoundInfoSchema>;
}>;

function generateRow(label: string, text: string | undefined) {
    if (!text) {
        return null;
    }

    return (
        <>
            <div className="w-100" />
            <Col xs="auto">{label}:</Col>
            <Col>{text}</Col>
        </>
    );
}

export default function RoundInfoModal(props: Props) {
    const {name, author, note1, note2, note3, note4, note5, source, imageUrl} =
        props.roundInfo;

    const [isOpen, setIsOpen] = useState(true);

    function hideModal() {
        setIsOpen(false);
    }

    useEffect(() => {
        setIsOpen(true);
    }, [props.launchTime]);

    return (
        <Modal
            show={isOpen}
            onHide={hideModal}
            aria-labelledby="levelInfoModalTitle"
        >
            <Modal.Header closeButton id="levelInfoModalTitle">
                <Modal.Title>{name}</Modal.Title>
            </Modal.Header>

            <Modal.Body>
                {imageUrl ? (
                    <img
                        src={imageUrl}
                        alt={`Screenshot of “${name}”`}
                        width="105"
                        height="80"
                        className="d-block mx-auto mb-3"
                    />
                ) : null}

                <Row>
                    <Col xs="auto">Author:</Col>
                    <Col>{author ? author : <em>(not set)</em>}</Col>

                    {generateRow('Note 1', note1)}
                    {generateRow('Note 2', note2)}
                    {generateRow('Note 3', note3)}
                    {generateRow('Note 4', note4)}
                    {generateRow('Note 5', note5)}
                    {generateRow('Source', source)}
                </Row>
            </Modal.Body>

            <Modal.Footer>
                <Button variant="outline-primary" onClick={hideModal}>
                    Close
                </Button>
            </Modal.Footer>
        </Modal>
    );
}
