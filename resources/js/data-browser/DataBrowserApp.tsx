import {LocationProvider, Route, Router} from 'preact-iso';

import {type DataBrowserListingType} from './DataBrowserListing';
import FilePath from './FilePath';

type Props = Readonly<{
    allDirectoriesListing: DataBrowserListingType;
    urlPrefix: string;
}>;

export default function DataBrowserApp({
    allDirectoriesListing,
    urlPrefix,
}: Props) {
    return (
        <LocationProvider scope={urlPrefix}>
            <Router>
                {[
                    <Route
                        path={urlPrefix + '/:path*'}
                        component={FilePath}
                        key="route"
                        allDirectoriesListing={allDirectoriesListing}
                    />,
                ]}
            </Router>
        </LocationProvider>
    );
}
