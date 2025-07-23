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
            if (baseGameSoundPath) {
                soundsConflictWithBaseGame.set(file.path, baseGameSoundPath);
            }
        } else {
            if (pathExistsInGameData(file.path)) {
                pathsOverwriteBaseGame.add(file.path);
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

    return {
        pathsOverwriteBaseGame,
        soundsConflictWithBaseGame,
        soundsWithSameFileNames,
    };
}
