import {promises as fs} from 'fs';
import path from 'path';

import checkForMods from '../check-for-mods';

test('ensure no false detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Level Editor Template.txt'
        ),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(false);
});

test('Neon Environment detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Neon Environment Detection Test.txt'
        ),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(true);
    expect(modRequirement.mod).toBe('Neon Environment');
});
