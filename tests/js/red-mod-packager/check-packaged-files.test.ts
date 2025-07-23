import fs from 'node:fs/promises';
import path from 'node:path';

import checkPackagedFiles from '../../../resources/js/red-mod-packager/check-packaged-files';
import {getFileNameFromPath} from '../../../resources/js/red-mod-packager/game-data-list';

test('does not warn for proper files', async () => {
    const modInfo = JSON.parse(
        await fs.readFile(
            path.resolve(
                __dirname,
                '../../../resources/mod-info/heavymetal-files.json'
            ),
            'utf-8'
        )
    ) as {trigger_codename: string; files: string[]};

    const result = checkPackagedFiles(
        modInfo.files.map((file) => {
            return {
                path: file,
                file: new File([], getFileNameFromPath(file)),
            };
        })
    );

    expect(result.pathsOverwriteBaseGame.size).toBe(0);
    expect(result.soundsConflictWithBaseGame.size).toBe(0);
    expect(result.soundsWithSameFileNames.size).toBe(0);
});

test('checks file paths that overwrite the base game', () => {
    const result = checkPackagedFiles([
        {
            path: 'Resources/Power Ups/Acid Ball.PowerUp',
            file: new File([], 'Acid Ball.PowerUp'),
        },
        {
            // test case sensitivity
            path: 'resources/ranks/ranks.object.txt',
            file: new File([], 'ranks.object.txt'),
        },
        {path: 'Sounds/Silly/Alien.ogg', file: new File([], 'Alien.ogg')}, // sounds are handled differently
    ]);

    expect(result.pathsOverwriteBaseGame.size).toBe(2);
    const values = result.pathsOverwriteBaseGame.values();
    expect(values.next().value).toBe('Resources/Power Ups/Acid Ball.PowerUp');
    expect(values.next().value).toBe('resources/ranks/ranks.object.txt');

    expect(result.soundsConflictWithBaseGame.size).toBe(1);
    expect(
        result.soundsConflictWithBaseGame.entries().next().value
    ).toStrictEqual(['Sounds/Silly/Alien.ogg', 'Sounds/Silly/Alien.ogg']);

    expect(result.soundsWithSameFileNames.size).toBe(0);
});

test('checks sound files with same file names', () => {
    const result = checkPackagedFiles([
        {path: 'Sounds/a/abc.ogg', file: new File([], 'abc.ogg')},
        {path: 'Sounds/b/abc.ogg', file: new File([], 'abc.ogg')},
        {path: 'Sounds/c/ABC.ogg', file: new File([], 'ABC.ogg')}, // test case sensitivity
    ]);

    expect(result.pathsOverwriteBaseGame.size).toBe(0);

    expect(result.soundsConflictWithBaseGame.size).toBe(0);

    expect(result.soundsWithSameFileNames.size).toBe(1);
    expect(result.soundsWithSameFileNames.entries().next().value).toStrictEqual(
        [
            'abc.ogg',
            ['Sounds/a/abc.ogg', 'Sounds/b/abc.ogg', 'Sounds/c/ABC.ogg'],
        ]
    );
});

test('checks sound file names that conflict with base game', () => {
    const result = checkPackagedFiles([
        {path: 'Sounds/a/Alien.ogg', file: new File([], 'Alien.ogg')},
        {path: 'Sounds/b/beE.OGg', file: new File([], 'beE.OGg')}, // test case sensitivity
    ]);

    expect(result.pathsOverwriteBaseGame.size).toBe(0);

    expect(result.soundsConflictWithBaseGame.size).toBe(2);
    const entries = result.soundsConflictWithBaseGame.entries();
    expect(entries.next().value).toStrictEqual([
        'Sounds/a/Alien.ogg',
        'Sounds/Silly/Alien.ogg',
    ]);
    expect(entries.next().value).toStrictEqual([
        'Sounds/b/beE.OGg',
        'Sounds/Silly/Bee.ogg',
    ]);

    expect(result.soundsWithSameFileNames.size).toBe(0);
});
