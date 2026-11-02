import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

export default function DataBrowserApp() {
    return (
        <Stack gap={3}>
            <Card as="section">
                <Card.Header as="h1">Data browser</Card.Header>

                <Card.Body>
                    <p>Hello from JS</p>
                </Card.Body>
            </Card>
        </Stack>
    );
}
