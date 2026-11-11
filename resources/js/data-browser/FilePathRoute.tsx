import {type RoutePropsForPath} from 'preact-iso';
import Alert from 'react-bootstrap/Alert';
import Card from 'react-bootstrap/Card';
import Stack from 'react-bootstrap/Stack';

import type {DataBrowserListingType} from './DataBrowserListing';
import FileViewer from './FileViewer';
import NavBreadcrumb from './NavBreadcrumb';
import parsePath from './parsePath';

function buildLink(item: string, dirname: string, urlPrefix: string) {
    return urlPrefix + '/' + (dirname !== '/' ? dirname : '') + item;
}

type Props = Readonly<
    RoutePropsForPath<'/:path*'> & {
        allDirectoriesListing: DataBrowserListingType;
        storageUrl: string;
        urlPrefix: string;
    }
>;

export default function FilePathRoute({
    params,
    allDirectoriesListing,
    storageUrl,
    urlPrefix,
}: Props) {
    const path = params.path ?? '';
    const parsed = parsePath(path, allDirectoriesListing);

    // todo update document.title

    return (
        <Stack gap={3}>
            <Card as="section">
                <Card.Header as="h1">Data browser</Card.Header>

                <Card.Body>
                    {parsed != null ? (
                        <NavBreadcrumb parsed={parsed} urlPrefix={urlPrefix} />
                    ) : null}

                    {parsed == null ? (
                        <Alert variant="danger">
                            This folder/file does not exist.
                        </Alert>
                    ) : 'list' in parsed ? (
                        <ul>
                            {parsed.list.directories.map((item) => {
                                return (
                                    <li key={item}>
                                        <a
                                            href={buildLink(
                                                item + '/',
                                                parsed.dirname,
                                                urlPrefix
                                            )}
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
                                            href={buildLink(
                                                item,
                                                parsed.dirname,
                                                urlPrefix
                                            )}
                                        >
                                            {item}
                                        </a>
                                    </li>
                                );
                            })}
                        </ul>
                    ) : 'filename' in parsed ? (
                        <FileViewer
                            storageUrl={storageUrl}
                            dirname={parsed.dirname}
                            filename={parsed.filename}
                        />
                    ) : null}
                </Card.Body>
            </Card>
        </Stack>
    );
}
