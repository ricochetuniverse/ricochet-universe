import fs from 'node:fs/promises';
import path from 'node:path';

import generateZip from '../../../resources/js/red-mod-packager/generate-zip';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

test('generates RED file', async () => {
    const sequence = new Uint8Array(
        await fs.readFile(
            path.resolve(
                FIXTURE_DIR,
                './red-mod-packager/Cache/Resources/Player Ship/Player Shot.Sequence'
            )
        )
    ).buffer;

    // Construct the file...
    const file = new File([sequence], 'Player Shot.Sequence', {
        type: '',
        lastModified: 1185540378000,
    });
    // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/File/webkitRelativePath
    file.webkitRelativePath =
        'Cache/Resources/Player Ship/Player Shot.Sequence';

    // Zip it...
    const zip = await generateZip([file], '');

    // Assert
    const packaged = await fs.readFile(
        path.resolve(FIXTURE_DIR, './red-mod-packager/packaged.red')
    );

    expect(await zip.arrayBuffer()).toStrictEqual(packaged.buffer);
});
