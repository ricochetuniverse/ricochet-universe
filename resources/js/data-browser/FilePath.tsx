import {useLocation, useRoute} from 'preact-iso';
import {Card, Stack} from 'react-bootstrap';

export default function FilePath() {
    const location = useLocation();
    const route = useRoute();

    console.log(location);
    console.log(route);

    // const path = route.params.path;

    return (
        <Stack gap={3}>
            <Card as="section">
                <Card.Header as="h1">Data browser</Card.Header>

                <Card.Body>
                    {/* <a
                        href={
                            '/data-browser/ricochet-infinity-windows-build-68/' +
                            Math.random()
                        }
                    >
                        Test
                    </a> */}
                </Card.Body>
            </Card>
        </Stack>
    );
}
