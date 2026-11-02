import {type RoutePropsForPath} from 'preact-iso';
import {Card, Stack} from 'react-bootstrap';

import type {DataBrowserListingType} from './DataBrowserListing';

type Props = Readonly<
    RoutePropsForPath<'/:path*'> & {
        allDirectoriesListing: DataBrowserListingType;
    }
>;

export default function FilePath({params, allDirectoriesListing}: Props) {
    console.log(allDirectoriesListing);
    console.log(params.path);

    const pathSplit = (params.path ?? '').split('/');
    const dirname = pathSplit.slice(0, -1).join('/');

    const listing = allDirectoriesListing.find((list) => {
        return list.prefix === dirname;
    });
    console.log(listing);

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
