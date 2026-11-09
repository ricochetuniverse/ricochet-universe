import type {DataBrowserListingType} from './DataBrowserListing';

export type ParsedPath =
    | {
          dirname: string;
          list: DataBrowserListingType[0];
      }
    | {
          dirname: string;
          filename: string | null;
      };

export default function parsePath(
    path: string,
    allDirectoriesListing: DataBrowserListingType
): ParsedPath | null {
    const pathSplit = path.split('/');

    const dirname = pathSplit.slice(0, -1).join('/') + '/';
    const filename = pathSplit[pathSplit.length - 1];

    for (let i = 0; i < allDirectoriesListing.length; i += 1) {
        const list = allDirectoriesListing[i];
        if (list.path === dirname) {
            if (filename === '') {
                return {
                    dirname,
                    list,
                };
            } else if (list.files.includes(filename)) {
                return {
                    dirname,
                    filename,
                };
            } else if (list.directories.includes(filename)) {
                // preact-iso router does not return `params.path` with ending slash,
                // navigating to /Resources/ will return `Resources`
                // so we need to redo the array search with the ending slash
                return parsePath(path + '/', allDirectoriesListing);
            }

            // file not found in this directory, fail early
            return null;
        }
    }

    return null;
}
