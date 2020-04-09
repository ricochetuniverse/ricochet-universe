import {promises as fs} from 'fs';
import path from 'path';

import {inflateFile, decodeFromUint8Array} from '../inflate-file';

test('level sets', async () => {
    const compressed = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Reflexive B Sides.RicochetLW'
        )
    );

    const decompressed = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Reflexive B Sides.RicochetLW.txt'
        )
    );

    const inflated = inflateFile(compressed.buffer);

    expect(inflated.utf8).toBe(decodeFromUint8Array(decompressed));
    expect(inflated.image).toBeNull();
});

test('sequences', async () => {
    const original = await fs.readFile(
        path.resolve(__dirname, './fixtures/Sequence1/Ball Rail Small.Sequence')
    );

    const text = await fs.readFile(
        path.resolve(
            __dirname,
            './fixtures/Sequence1/Ball Rail Small.Sequence (decompressed).txt'
        ),
        'utf8'
    );

    const image = await fs.readFile(
        path.resolve(__dirname, './fixtures/Sequence1/Ball Rail Small.jpg')
    );

    const inflated = inflateFile(original.buffer);

    expect(inflated.utf8).toBe(text);
    expect(inflated.image).toStrictEqual(new Uint8Array(image));
});
