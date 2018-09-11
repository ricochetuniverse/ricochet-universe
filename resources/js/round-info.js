import $ from 'jquery';
// noinspection ES6UnusedImports
import {Component, h, render} from 'preact';
import {
    Button,
    Col,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader,
    Row,
} from 'reactstrap';

let modalWrap;

$('.roundInfo__link').on('click', function(ev) {
    ev.preventDefault();

    const $link = $(this);

    const props = {
        name: $link.data('round-name'),
        author: $link.data('round-author'),
        note1: $link.data('round-note-1'),
        note2: $link.data('round-note-2'),
        note3: $link.data('round-note-3'),
        note4: $link.data('round-note-4'),
        note5: $link.data('round-note-5'),
        source: $link.data('round-source'),
        imageUrl: $link.data('round-image-url'),
    };

    if (!modalWrap) {
        modalWrap = document.createElement('div');
        document.body.appendChild(modalWrap);
    }

    render(
        <RoundInfoModal {...props} />,
        modalWrap,
        modalWrap.lastElementChild
    );
});

class RoundInfoModal extends Component {
    state = {
        opened: true,
    };

    toggleModal = () => {
        this.setState((prevState) => {
            return {
                opened: !prevState.opened,
            };
        });
    };

    render() {
        return (
            <Modal
                isOpen={this.state.opened}
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

                        {/* Preact doesn't support fragments yet :( */}
                        <div className="w-100" />
                        {this.props.note1 ? <Col xs="auto">Note 1:</Col> : null}
                        {this.props.note1 ? (
                            <Col>{this.props.note1}</Col>
                        ) : null}

                        <div className="w-100" />
                        {this.props.note2 ? <Col xs="auto">Note 2:</Col> : null}
                        {this.props.note2 ? (
                            <Col>{this.props.note2}</Col>
                        ) : null}

                        <div className="w-100" />
                        {this.props.note3 ? <Col xs="auto">Note 3:</Col> : null}
                        {this.props.note3 ? (
                            <Col>{this.props.note3}</Col>
                        ) : null}

                        <div className="w-100" />
                        {this.props.note4 ? <Col xs="auto">Note 4:</Col> : null}
                        {this.props.note4 ? (
                            <Col>{this.props.note4}</Col>
                        ) : null}

                        <div className="w-100" />
                        {this.props.note5 ? <Col xs="auto">Note 5:</Col> : null}
                        {this.props.note5 ? (
                            <Col>{this.props.note5}</Col>
                        ) : null}

                        <div className="w-100" />
                        {this.props.source ? (
                            <Col xs="auto">Source:</Col>
                        ) : null}
                        {this.props.source ? (
                            <Col>{this.props.source}</Col>
                        ) : null}
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
