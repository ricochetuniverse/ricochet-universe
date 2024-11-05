// @flow

import JSZip from 'jszip';

function withoutPrefix(text: string, prefix: string) {
    return text.substring(prefix.length, text.length);
}

function getSortedFileList(files: FileList, stripDirectoryPrefix: string) {
    const sorted: Array<{|path: string, file: File|}> = [];

    for (let i = 0, len = files.length; i < len; i += 1) {
        const file = files[i];

        const path = withoutPrefix(
            // $FlowFixMe[prop-missing] https://developer.mozilla.org/en-US/docs/Web/API/File/webkitRelativePath
            (file.webkitRelativePath: string),
            stripDirectoryPrefix
        );

        if (path.match(/(desktop\.ini|thumbs\.db|\.DS_Store)$/i)) {
            continue;
        }

        sorted.push({
            path,
            file,
        });
    }

    return sorted.sort((a, b) => {
        if (a.path < b.path) {
            return -1;
        } else if (a.path > b.path) {
            return 1;
        }

        return 0;
    });
}

export default function generateZip(
    files: FileList,
    stripDirectoryPrefix: string
): Promise<Blob> {
    const zip = new JSZip();

    let sequence = Promise.resolve();

    // Files need to be added alphabetically in sequence for the game's zip engine
    getSortedFileList(files, stripDirectoryPrefix).forEach((fileInfo) => {
        sequence = sequence.then(() => {
            return new Promise((resolve) => {
                const reader = new FileReader();

                reader.onload = () => {
                    zip.file(fileInfo.path, reader.result, {
                        binary: true,
                        createFolders: false,
                        date: new Date(fileInfo.file.lastModified),
                    });

                    resolve();
                };

                reader.onerror = (ex) => {
                    throw ex;
                };

                reader.readAsArrayBuffer(fileInfo.file);
            });
        });
    });

    sequence = sequence.then(() => {
        // The original instructions used the Unix `zip` utility
        // It's proven to work reliably, so let's reuse that fact
        return zip
            .generateAsync({type: 'blob', platform: 'UNIX'})
            .then((content) => {
                return Promise.resolve(
                    new Blob([content], {
                        type: 'application/zip',
                    })
                );
            });
    });

    // $FlowFixMe[incompatible-return]
    return sequence;
}
