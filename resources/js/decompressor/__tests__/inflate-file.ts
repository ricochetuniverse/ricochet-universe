import fs from 'node:fs/promises';
import path from 'node:path';

import {inflateFile, decodeFromUint8Array} from '../inflate-file';

const FIXTURE_DIR = path.resolve(__dirname, '../../../../tests/fixtures/');

test('level sets', async () => {
    const compressed = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Reflexive B Sides.RicochetLW')
    );

    const decompressed = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Reflexive B Sides.RicochetLW.txt')
    );

    // Need to force convert to Uint8Array
    // https://github.com/nodejs/node/issues/11132
    const inflated = inflateFile(new Uint8Array(compressed).buffer);

    expect(inflated.utf8).toBe(decodeFromUint8Array(decompressed));
    expect(inflated.image).toBeNull();
});

test('sequences', async () => {
    const original = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './decompressor/Sequence1/Ball Rail Small.Sequence'
        )
    );

    const text = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './decompressor/Sequence1/Ball Rail Small.Sequence (decompressed).txt'
        ),
        'utf8'
    );

    const image = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './decompressor/Sequence1/Ball Rail Small.jpg'
        )
    );

    const inflated = inflateFile(new Uint8Array(original).buffer);

    expect(inflated.utf8).toBe(text);
    expect(inflated.image).toStrictEqual(new Uint8Array(image));
});

test('frames', async () => {
    const original = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './decompressor/Frame/MainMenuShipShield.Frame'
        )
    );

    const image = await fs.readFile(
        path.resolve(FIXTURE_DIR, './decompressor/Frame/MainMenuShipShield.jpg')
    );

    const inflated = inflateFile(new Uint8Array(original).buffer);

    expect(inflated.image).toStrictEqual(new Uint8Array(image));
});
