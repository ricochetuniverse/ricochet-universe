import assert from 'node:assert/strict';
import fs from 'node:fs/promises';
import path from 'node:path';

import checkForMods from '../../../resources/js/decompressor/check-for-mods';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

test('ensure no false detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Level Editor Template.txt'),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(false);
});

test('Neon Environment detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Neon Environment Detection Test.txt'),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(true);
    assert(modRequirement.result);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['Neon Environment']);
});

test('Heavy Metal Environment detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './Heavy Metal Environment Detection Test.txt'
        ),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(true);
    assert(modRequirement.result);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['Heavy Metal Environment']);
});

test('HEX detection', async () => {
    const levelSet = await fs.readFile(
        path.resolve(FIXTURE_DIR, './HEX Detection Test.txt'),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(true);
    assert(modRequirement.result);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['HEX']);
});

test('mod powerup inside lottery', async () => {
    const levelSet = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Mod powerup inside lottery.txt'),
        'utf-8'
    );

    const modRequirement = checkForMods(levelSet);

    expect(modRequirement.result).toBe(true);
    assert(modRequirement.result);
    expect(modRequirement.mods).toHaveLength(1);
    expect(modRequirement.mods).toEqual(['Neon Environment']);
});
