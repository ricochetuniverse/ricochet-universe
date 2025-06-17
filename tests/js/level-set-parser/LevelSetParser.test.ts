import fs from 'node:fs/promises';
import path from 'node:path';

import {parse} from '../../../resources/js/level-set-parser/LevelSetParser';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

test('parses Lost Worlds level set', async () => {
    const levelSet = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Reflexive B Sides.RicochetLW.txt')
    );

    const results = parse(levelSet);

    expect(results.author).toBe('Reflexive Entertainment');
    expect(results.description).toBe('');

    expect(results.rounds).toHaveLength(26);
    expect(results.rounds[0].name).toBe('Whirlpool');
    expect(results.rounds[0].author).toBe('Ion');
    expect(results.rounds[0].notes[0]).toBe(
        '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed'
    );
    expect(results.rounds[0].source).toBe('Ion/Reflexive B Sides/1');
});

test('parses Infinity level set', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './Rico at the Brick Factory/Rico at the Brick Factory.RicochetI.txt'
        )
    );

    const thumbnail = await fs.readFile(
        path.resolve(FIXTURE_DIR, './Rico at the Brick Factory/thumbnail.jpg')
    );

    const results = parse(levelSet);

    expect(results.author).toBe('Josef L');
    expect(results.description).toBe(
        'Just some relaxing quick levels I hope you find fun  .. Some helpful power ups to help you on your way . Enjoy .'
    );

    expect(results.rounds).toHaveLength(13);
    expect(results.rounds[0].name).toBe('Arrived');
    expect(results.rounds[0].author).toBe('Josef L');
    expect(results.rounds[0].source).toBe('/Rico at the Brick Factory/1');
    expect(results.rounds[0].thumbnail).toStrictEqual(thumbnail);
});

test('thumbnail of round with custom brick layer effect', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './custom-brick-layer-thumbnail-test/Level.txt'
        )
    );

    const thumbnail = await fs.readFile(
        path.resolve(
            FIXTURE_DIR,
            './custom-brick-layer-thumbnail-test/thumbnail.jpg'
        )
    );

    const results = parse(levelSet);

    expect(results.rounds).toHaveLength(2);
    expect(results.rounds[0].name).toBe('Main');
    expect(results.rounds[0].thumbnail).toStrictEqual(thumbnail);
});
