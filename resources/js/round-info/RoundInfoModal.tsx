import {useEffect, useState} from 'preact/hooks';
import Button from 'react-bootstrap/Button';
import Col from 'react-bootstrap/Col';
import Modal from 'react-bootstrap/Modal';
import Row from 'react-bootstrap/Row';

type Props = Readonly<{
    launchTime?: number;
    name?: string;
    author?: string;
    note1?: string;
    note2?: string;
    note3?: string;
    note4?: string;
    note5?: string;
    source?: string;
    imageUrl?: string;
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
                <Modal.Title>{props.name}</Modal.Title>
            </Modal.Header>

            <Modal.Body>
                {props.imageUrl ? (
                    <img
                        src={props.imageUrl}
                        alt={`Screenshot of “${props.name}”`}
                        width="105"
                        height="80"
                        className="d-block mx-auto mb-3"
                    />
                ) : null}

                <Row>
                    <Col xs="auto">Author:</Col>
                    <Col>
                        {props.author ? props.author : <em>(not set)</em>}
                    </Col>

                    {generateRow('Note 1', props.note1)}
                    {generateRow('Note 2', props.note2)}
                    {generateRow('Note 3', props.note3)}
                    {generateRow('Note 4', props.note4)}
                    {generateRow('Note 5', props.note5)}
                    {generateRow('Source', props.source)}
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
