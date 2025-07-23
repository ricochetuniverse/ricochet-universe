import {
    findFileNameInAnyFolderInGameData,
    getFileNameFromPath,
    pathExistsInGameData,
} from '../../../resources/js/red-mod-packager/game-data-list';

test('gets file name from path', () => {
    expect(getFileNameFromPath('a/b/c.txt')).toBe('c.txt');
    expect(getFileNameFromPath('a.txt')).toBe('a.txt');
});

test('find file name in any folder in game data', () => {
    expect(findFileNameInAnyFolderInGameData('Ranks.Object.txt')).toBe(
        'Resources/Ranks/Ranks.Object.txt'
    );
    expect(findFileNameInAnyFolderInGameData('nope.txt')).toBeUndefined();
});

test('path exists in game data', () => {
    expect(pathExistsInGameData('Resources/Ranks/Ranks.Object.txt')).toBe(true);
    expect(pathExistsInGameData('Ranks.Object.txt')).toBe(false);
    expect(pathExistsInGameData('nope.txt')).toBe(false);
});
