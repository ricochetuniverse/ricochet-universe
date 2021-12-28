import {promises as fs} from 'fs';
import path from 'path';

import {parse} from '../LevelSetParser';

test('parses Lost Worlds level set', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Reflexive B Sides.RicochetLW.txt'
        )
    );

    const results = parse(levelSet);

    expect(results.author).toEqual('Reflexive Entertainment');
    expect(results.description).toEqual('');

    expect(results.rounds).toHaveLength(26);
    expect(results.rounds[0].name).toEqual('Whirlpool');
    expect(results.rounds[0].author).toEqual('Ion');
    expect(results.rounds[0].notes[0]).toEqual(
        '2 rings hidden under obstacles. Obstacles move when all 3 PU bricks over rings are destroyed'
    );
    expect(results.rounds[0].source).toEqual('Ion/Reflexive B Sides/1');
});

test('parses Infinity level set', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Rico at the Brick Factory/Rico at the Brick Factory.RicochetI.txt'
        )
    );

    const thumbnail = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/Rico at the Brick Factory/thumbnail.jpg'
        )
    );

    const results = parse(levelSet);

    expect(results.author).toEqual('Josef L');
    expect(results.description).toEqual(
        'Just some relaxing quick levels I hope you find fun  .. Some helpful power ups to help you on your way . Enjoy .'
    );

    expect(results.rounds).toHaveLength(13);
    expect(results.rounds[0].name).toEqual('Arrived');
    expect(results.rounds[0].author).toEqual('Josef L');
    expect(results.rounds[0].source).toEqual('/Rico at the Brick Factory/1');
    expect(results.rounds[0].thumbnail).toStrictEqual(thumbnail);
});

test('thumbnail of round with custom brick layer effect', async () => {
    const levelSet = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/custom-brick-layer-thumbnail-test/Level.txt'
        )
    );

    const thumbnail = await fs.readFile(
        path.resolve(
            __dirname,
            '../../../../tests/fixtures/custom-brick-layer-thumbnail-test/thumbnail.jpg'
        )
    );

    const results = parse(levelSet);

    expect(results.rounds).toHaveLength(2);
    expect(results.rounds[0].name).toEqual('Main');
    expect(results.rounds[0].thumbnail).toStrictEqual(thumbnail);
});
