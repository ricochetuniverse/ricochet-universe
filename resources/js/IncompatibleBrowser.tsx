import {Alert} from 'reactstrap';

export default function IncompatibleBrowser() {
    return (
        <Alert color="danger" fade={false} className="m-0">
            Sorry, your browser isn’t supported by this tool. Please use another
            browser such as the latest{' '}
            <a
                href="https://www.google.com/chrome/"
                className="alert-link"
                target="_blank"
                rel="noreferrer"
            >
                Chrome
            </a>{' '}
            or{' '}
            <a
                href="https://getfirefox.com"
                className="alert-link"
                target="_blank"
                rel="noreferrer"
            >
                Firefox
            </a>
            .
        </Alert>
    );
}
