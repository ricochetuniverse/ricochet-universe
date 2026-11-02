import nullthrows from 'nullthrows';
import {render} from 'preact';

import DataBrowserApp from './DataBrowserApp';
import {DataBrowserListingSchema} from './DataBrowserListing';

const root = document.getElementById('data-browser-root');
const allDirectoriesListing = DataBrowserListingSchema.parse(
    JSON.parse(
        nullthrows(document.getElementById('data-browser-all-directories'))
            .textContent
    )
);

if (root) {
    render(
        <DataBrowserApp
            allDirectoriesListing={allDirectoriesListing}
            urlPrefix={nullthrows(root.dataset['urlPrefix'])}
        />,
        root
    );
}
