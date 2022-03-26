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

    expect(modRequirement.result).toEqual(false);
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

    expect(modRequirement.result).toEqual(true);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['Neon Environment']);
});

test('Heavy Metal Environment detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Heavy Metal Environment Detection Test.txt'
        ),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toEqual(true);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['Heavy Metal Environment']);
});

test('HEX detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/HEX Detection Test.txt'
        ),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toEqual(true);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['HEX']);
});
