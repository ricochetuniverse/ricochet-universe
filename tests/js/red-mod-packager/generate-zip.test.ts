import fs from 'node:fs/promises';
import path from 'node:path';

import generateZip from '../../../resources/js/red-mod-packager/generate-zip';

import getTestFile from './getTestFile';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

test('generates RED file', async () => {
    const sequence = await getTestFile();

    // Zip it...
    const result = await generateZip([sequence], 'My Mod/');

    // Compare
    const existing = await fs.readFile(
        path.resolve(FIXTURE_DIR, './red-mod-packager/packaged.red')
    );

    expect(await result.zip.arrayBuffer()).toStrictEqual(existing.buffer);
});
