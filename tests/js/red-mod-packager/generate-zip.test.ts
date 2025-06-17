import assert from 'node:assert/strict';
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

    const packaged = await fs.readFile(
        path.resolve(FIXTURE_DIR, './red-mod-packager/packaged.red')
    );

    // Construct the file...
    const file = new File([sequence], 'Player Shot.Sequence', {
        type: '',
        lastModified: 1185540378000,
    });
    // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/File/webkitRelativePath
    file.webkitRelativePath =
        'Cache/Resources/Player Ship/Player Shot.Sequence';

    // Zip it...
    // @ts-expect-error function should be changed to File[] instead of FileList
    const zipBlob = await generateZip([file], '');

    const zipArrayBuffer: ArrayBuffer = await new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (ev) => {
            assert(ev.target);
            resolve(ev.target.result as ArrayBuffer);
        };
        reader.onerror = reject;
        reader.readAsArrayBuffer(zipBlob);
    });

    expect(new Uint8Array(zipArrayBuffer)).toStrictEqual(
        new Uint8Array(packaged)
    );
});
