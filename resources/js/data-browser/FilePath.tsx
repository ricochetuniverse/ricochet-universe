import {Fragment} from 'preact';
import {type RoutePropsForPath} from 'preact-iso';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

import type {DataBrowserListingType} from './DataBrowserListing';
import parsePath from './parsePath';

type Props = Readonly<
    RoutePropsForPath<'/:path*'> & {
        allDirectoriesListing: DataBrowserListingType;
        urlPrefix: string;
    }
>;

export default function FilePath({
    params,
    allDirectoriesListing,
    urlPrefix,
}: Props) {
    const path = params.path ?? '';

    const parsed = parsePath(path, allDirectoriesListing);
    console.log(parsed);

    // todo update document.title

    return (
        <Stack gap={3}>
            <Card as="section">
                <Card.Header as="h1">Data browser</Card.Header>

                <Card.Body>
                    {parsed?.dirname
                        .split('/')
                        .filter(Boolean)
                        .map((item, index) => {
                            // todo buggy in subfolders
                            return (
                                <Fragment key={index.toString() + item}>
                                    <a href={urlPrefix + '/' + item + '/'}>
                                        {item}
                                    </a>
                                    /
                                </Fragment>
                            );
                        })}

                    {parsed == null ? (
                        <Alert variant="danger">
                            This folder/file does not exist.
                        </Alert>
                    ) : (
                        <ul>
                            {parsed.list.directories.map((item) => {
                                return (
                                    <li key={item}>
                                        <a
                                            href={
                                                urlPrefix +
                                                '/' +
                                                parsed.dirname +
                                                item +
                                                '/'
                                            }
                                        >
                                            {item}
                                        </a>
                                    </li>
                                );
                            })}

                            {parsed.list.files.map((item) => {
                                return (
                                    <li key={item}>
                                        <a
                                            href={
                                                urlPrefix +
                                                '/' +
                                                parsed.dirname +
                                                item
                                            }
                                        >
                                            {item}
                                        </a>
                                    </li>
                                );
                            })}
                        </ul>
                    )}
                </Card.Body>
            </Card>
        </Stack>
    );
}
