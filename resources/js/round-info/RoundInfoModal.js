// @flow strict

import {Fragment, h} from 'preact';
import {useEffect, useState} from 'preact/hooks';

import {
    Button,
    Col,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
    Row,
} from 'reactstrap';

type Props = $ReadOnly<{|
    launchTime: number,

    name: string,
    author: string,
    note1: string,
    note2: string,
    note3: string,
    note4: string,
    note5: string,
    source: string,
    imageUrl: string,
|}>;

function generateRow(label, text) {
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

export default function RoundInfoModal(props: Props): React.Node {
    const [isOpen, setIsOpen] = useState(true);

    function toggleModal() {
        setIsOpen(!isOpen);
    }

    useEffect(() => {
        setIsOpen(true);
    }, [props.launchTime]);

    return (
        <Modal
            isOpen={isOpen}
            toggle={toggleModal}
            fade={false}
            labelledBy="levelInfoModalTitle"
        >
            <ModalHeader toggle={toggleModal} id="levelInfoModalTitle">
                {props.name}
            </ModalHeader>

            <ModalBody>
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
            </ModalBody>

            <ModalFooter>
                <Button outline color="primary" onClick={toggleModal}>
                    Close
                </Button>
            </ModalFooter>
        </Modal>
    );
}
