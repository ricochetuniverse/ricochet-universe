// @flow strict

import {Component, Fragment, h} from 'preact';

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
    currentOpenCount: number,

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

type State = $ReadOnly<{|
    isOpen: boolean,
    previousOpenCount: number,
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

export default class RoundInfoModal extends Component<Props, State> {
    state: State = {
        isOpen: true,
        previousOpenCount: 0,
    };

    toggleModal: () => void = () => {
        this.setState((prevState) => {
            return {
                isOpen: !prevState.isOpen,
            };
        });
    };

    componentDidMount() {
        this.setState({
            previousOpenCount: this.props.currentOpenCount,
        });
    }

    componentDidUpdate() {
        if (this.props.currentOpenCount !== this.state.previousOpenCount) {
            this.setState({
                isOpen: true,
                previousOpenCount: this.props.currentOpenCount,
            });
        }
    }

    render(): React.Node {
        return (
            <Modal
                isOpen={this.state.isOpen}
                toggle={this.toggleModal}
                fade={false}
                labelledBy="levelInfoModalTitle"
            >
                <ModalHeader toggle={this.toggleModal} id="levelInfoModalTitle">
                    {this.props.name}
                </ModalHeader>

                <ModalBody>
                    {this.props.imageUrl ? (
                        <img
                            src={this.props.imageUrl}
                            alt={'Screenshot of “' + this.props.name + '”'}
                            width="105"
                            height="80"
                            className="d-block mx-auto mb-3"
                        />
                    ) : null}

                    <Row>
                        <Col xs="auto">Author:</Col>
                        <Col>
                            {this.props.author ? (
                                this.props.author
                            ) : (
                                <em>(not set)</em>
                            )}
                        </Col>

                        {generateRow('Note 1', this.props.note1)}
                        {generateRow('Note 2', this.props.note2)}
                        {generateRow('Note 3', this.props.note3)}
                        {generateRow('Note 4', this.props.note4)}
                        {generateRow('Note 5', this.props.note5)}
                        {generateRow('Source', this.props.source)}
                    </Row>
                </ModalBody>

                <ModalFooter>
                    <Button outline color="primary" onClick={this.toggleModal}>
                        Close
                    </Button>
                </ModalFooter>
            </Modal>
        );
    }
}
