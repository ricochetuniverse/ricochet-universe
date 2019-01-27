import JSZip from 'jszip';

function withoutPrefix(text, prefix) {
    return text.substring(prefix.length, text.length);
}

function getSortedFileList(files, stripDirectoryPrefix) {
    const sorted = []; // {path: string, file: string}

    for (let i = 0, len = files.length; i < len; i += 1) {
        const file = files[i];

        const path = withoutPrefix(
            file.webkitRelativePath,
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

export default function generateZip(files, stripDirectoryPrefix) {
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
                        date: fileInfo.file.lastModifiedDate,
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
                const blob = new Blob([content], {
                    type: 'application/zip',
                });

                return Promise.resolve(window.URL.createObjectURL(blob));
            });
    });

    return sequence;
}
