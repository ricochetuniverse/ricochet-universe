import {Component, type JSX} from 'preact';
import {Suspense} from 'preact/compat';
import Button from 'react-bootstrap/Button';

import SuspenseComponentLoadingText from './SuspenseComponentLoadingText';

type Props = Readonly<{
    children: React.ReactNode;
    fallback?: (status: string | JSX.Element) => React.ReactNode;
    loadingText?: JSX.Element;
    retry?: () => void;
}>;

type State = Readonly<{
    error: Error | null;
}>;

export default class SuspenseComponent extends Component<Props, State> {
    state: State = {
        error: null,
    };

    static getDerivedStateFromError(error: Error) {
        return {error};
    }

    retry = () => {
        if (this.props.retry) {
            this.props.retry();
        }
        this.setState({error: null});
    };

    render() {
        const {
            fallback = (status) => status,
            loadingText = <SuspenseComponentLoadingText />,
        } = this.props;

        if (this.state.error) {
            return fallback(
                <div className="d-flex align-items-center">
                    Failed to load
                    {this.props.retry ? (
                        <Button
                            variant="outline-primary"
                            className="ms-3"
                            onClick={this.retry}
                        >
                            Retry
                        </Button>
                    ) : null}
                </div>
            );
        }

        return (
            <Suspense fallback={fallback(loadingText)}>
                {this.props.children}
            </Suspense>
        );
    }
}
