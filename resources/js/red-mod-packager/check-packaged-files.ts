import {
    findFileNameInAnyFolderInGameData,
    getFileNameFromPath,
    pathExistsInGameData,
} from './game-data-list';
import {type FileWithPath} from './generate-zip';

function isSoundFile(path: string): boolean {
    const pathLowercase = path.toLowerCase();
    return (
        pathLowercase.startsWith('sounds/') && pathLowercase.endsWith('.ogg')
    );
}

export default function checkPackagedFiles(files: FileWithPath[]): {
    pathsOverwriteBaseGame: Set<string>;
    soundsConflictWithBaseGame: Map<string, string>;
    soundsWithSameFileNames: Map<string, string[]>;
} {
    const pathsOverwriteBaseGame = new Set<string>();
    const groups = new Map<string, {fileName: string; paths: string[]}>();
    const soundsConflictWithBaseGame = new Map<string, string>();

    for (const file of files) {
        if (pathExistsInGameData(file.path)) {
            pathsOverwriteBaseGame.add(file.path);
        }

        if (isSoundFile(file.path)) {
            const fileName = getFileNameFromPath(file.path);
            const fileNameLowercase = fileName.toLowerCase();
            if (!groups.has(fileNameLowercase)) {
                groups.set(fileNameLowercase, {fileName, paths: []});
            }
            groups.get(fileNameLowercase)?.paths.push(file.path);

            // Check conflict with base game
            const baseGameSoundPath =
                findFileNameInAnyFolderInGameData(fileNameLowercase);
            if (baseGameSoundPath && !pathsOverwriteBaseGame.has(file.path)) {
                soundsConflictWithBaseGame.set(file.path, baseGameSoundPath);
            }
        }
    }

    // Check sound files with same file name on different folders
    const soundsWithSameFileNames = new Map<string, string[]>();
    for (const {fileName, paths} of groups.values()) {
        if (paths.length > 1) {
            soundsWithSameFileNames.set(fileName, paths);
        }
    }

    // If there is a sound file with the exact path to a base game sound, then
    // we assume it's supposed to overwrite the base game, so don't show an
    // inaccurate warning message about some game engine bug
    for (const [
        path,
        baseGameSoundPath,
    ] of soundsConflictWithBaseGame.entries()) {
        if (pathsOverwriteBaseGame.has(baseGameSoundPath)) {
            soundsConflictWithBaseGame.delete(path);
        }
    }

    return {
        pathsOverwriteBaseGame,
        soundsConflictWithBaseGame,
        soundsWithSameFileNames,
    };
}
