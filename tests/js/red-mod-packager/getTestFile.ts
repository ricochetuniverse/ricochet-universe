import fs from 'node:fs/promises';
import path from 'node:path';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

export default async function getTestFile() {
    const sequence = new Uint8Array(
        await fs.readFile(
            path.resolve(
                FIXTURE_DIR,
                './game-data/Cache/Resources/Player Ship/Player Shot.Sequence'
            )
        )
    ).buffer;

    const file = new File([sequence], 'Player Shot.Sequence', {
        type: '',
        lastModified: 1185540378000,
    });
    // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/File/webkitRelativePath
    file.webkitRelativePath =
        'My Mod/Cache/Resources/Player Ship/Player Shot.Sequence';

    return file;
}
