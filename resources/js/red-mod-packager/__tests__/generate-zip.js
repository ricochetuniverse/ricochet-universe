import {promises as fs} from 'fs';
import path from 'path';

import generateZip from '../generate-zip';

test('generates RED file', async () => {
    const sequence = new Uint8Array(
        await fs.readFile(
            path.resolve(
                __dirname,
                './fixtures/Cache/Resources/Player Ship/Player Shot.Sequence'
            )
        )
    ).buffer;

    const packaged = await fs.readFile(
        path.resolve(__dirname, './fixtures/packaged.red')
    );

    // Construct the file...
    const file = new File([sequence], 'Player Shot.Sequence', {
        type: '',
        lastModified: 1185540378000,
    });
    file.webkitRelativePath =
        'Cache/Resources/Player Ship/Player Shot.Sequence';

    // Zip it...
    const zipBlob = await generateZip([file], '');

    const zipArrayBuffer = await new Promise((resolve, reject) => {
        const reader = new FileReader();
        reader.onload = (ev) => {
            resolve(ev.target.result);
        };
        reader.onerror = reject;
        reader.readAsArrayBuffer(zipBlob);
    });

    expect(new Uint8Array(zipArrayBuffer)).toStrictEqual(
        new Uint8Array(packaged)
    );
});
