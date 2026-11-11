import {LocationProvider, Route, Router} from 'preact-iso';

import {type DataBrowserListingType} from './DataBrowserListing';
import FilePathRoute from './FilePathRoute';

type Props = Readonly<{
    allDirectoriesListing: DataBrowserListingType;
    storageUrl: string;
    urlPrefix: string;
}>;

export default function DataBrowserApp({
    allDirectoriesListing,
    storageUrl,
    urlPrefix,
}: Props) {
    return (
        <LocationProvider scope={urlPrefix + '/'}>
            <Router>
                {[
                    <Route
                        path={urlPrefix + '/:path*'}
                        component={FilePathRoute}
                        key="route"
                        allDirectoriesListing={allDirectoriesListing}
                        storageUrl={storageUrl}
                        urlPrefix={urlPrefix}
                    />,
                ]}
            </Router>
        </LocationProvider>
    );
}
