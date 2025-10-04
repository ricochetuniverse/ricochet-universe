import JSZip from 'jszip';

export type FileWithPath = {
    path: string;
    file: File;
};

function withoutPrefix(text: string, prefix: string) {
    return text.substring(prefix.length, text.length);
}

function getSortedFileList(files: File[], stripDirectoryPrefix: string) {
    const sorted: Array<FileWithPath> = [];

    for (let i = 0, len = files.length; i < len; i += 1) {
        const file = files[i];

        const path = withoutPrefix(
            file.webkitRelativePath,
            stripDirectoryPrefix
        );

        if (path.match(/(?:desktop\.ini|thumbs\.db|\.DS_Store)$/i)) {
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

export default async function generateZip(
    files: File[],
    stripDirectoryPrefix: string
): Promise<{zip: Blob; files: Array<FileWithPath>}> {
    const zip = new JSZip();

    // Files need to be added alphabetically *in sequence* for the game's zip
    // engine, doing `await`s inside a `for of` loop seems inefficient, but
    // we are doing this to avoid race conditions
    const filesWithPath = getSortedFileList(files, stripDirectoryPrefix);
    for (const fileInfo of filesWithPath) {
        await new Promise((resolve, reject) => {
            const reader = new FileReader();

            reader.onload = () => {
                if (!(reader.result instanceof ArrayBuffer)) {
                    throw new Error('Expected ArrayBuffer');
                }

                zip.file(fileInfo.path, reader.result, {
                    binary: true,
                    createFolders: false,
                    date: new Date(fileInfo.file.lastModified),
                });

                resolve(true);
            };

            reader.onerror = reject;

            reader.readAsArrayBuffer(fileInfo.file);
        });
    }

    // The original instructions used the Unix `zip` utility
    // It's proven to work reliably, so let's reuse that fact
    return {
        zip: await zip.generateAsync({
            type: 'blob',
            platform: 'UNIX',
        }),
        files: filesWithPath,
    };
}
