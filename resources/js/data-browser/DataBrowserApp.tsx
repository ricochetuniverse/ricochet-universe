import {LocationProvider, Route, Router} from 'preact-iso';

import {type DataBrowserListingType} from './DataBrowserListing';
import FilePathRoute from './FilePathRoute';

type Props = Readonly<{
    allDirectoriesListing: DataBrowserListingType;
    urlPrefix: string;
}>;

export default function DataBrowserApp({
    allDirectoriesListing,
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
                        urlPrefix={urlPrefix}
                    />,
                ]}
            </Router>
        </LocationProvider>
    );
}
