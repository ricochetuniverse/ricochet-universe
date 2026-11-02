import {LocationProvider, Route, Router} from 'preact-iso';

import FilePath from './FilePath';

type Props = Readonly<{
    urlPrefix: string;
}>;

export default function DataBrowserApp(props: Props) {
    return (
        <LocationProvider scope={props.urlPrefix}>
            <Router>
                {[
                    <Route
                        path={props.urlPrefix + '/:path*'}
                        component={FilePath}
                        key="route"
                    />,
                ]}
            </Router>
        </LocationProvider>
    );
}
