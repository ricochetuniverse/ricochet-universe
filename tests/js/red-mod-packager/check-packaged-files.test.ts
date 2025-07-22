import fs from 'node:fs/promises';
import path from 'node:path';

import checkPackagedFiles from '../../../resources/js/red-mod-packager/check-packaged-files';

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
                file: new File([], file.substring(file.lastIndexOf('/') + 1)),
            };
        })
    );

    expect(result.sameFileNames.size).toBe(0);
    expect(result.conflictWithBaseGame.size).toBe(0);
});

test('checks sound files with same file names', async () => {
    const result = checkPackagedFiles([
        {path: 'Sounds/a/abc.ogg', file: new File([], 'abc.ogg')},
        {path: 'Sounds/b/abc.ogg', file: new File([], 'abc.ogg')},
        {path: 'Sounds/c/ABC.ogg', file: new File([], 'ABC.ogg')}, // test case sensitivity
    ]);

    expect(result.sameFileNames.size).toBe(1);
    expect(result.sameFileNames.entries().next().value).toStrictEqual([
        'abc.ogg',
        ['Sounds/a/abc.ogg', 'Sounds/b/abc.ogg', 'Sounds/c/ABC.ogg'],
    ]);

    expect(result.conflictWithBaseGame.size).toBe(0);
});

test('checks sound file names that conflict with base game', async () => {
    const result = checkPackagedFiles([
        {path: 'Sounds/a/Alien.ogg', file: new File([], 'Alien.ogg')},
        {path: 'Sounds/b/beE.OGg', file: new File([], 'beE.OGg')}, // test case sensitivity
    ]);

    expect(result.sameFileNames.size).toBe(0);

    expect(result.conflictWithBaseGame.size).toBe(2);
    const entries = result.conflictWithBaseGame.entries();
    expect(entries.next().value).toStrictEqual([
        'Sounds/a/Alien.ogg',
        'Sounds/Silly/Alien.ogg',
    ]);
    expect(entries.next().value).toStrictEqual([
        'Sounds/b/beE.OGg',
        'Sounds/Silly/Bee.ogg',
    ]);
});
