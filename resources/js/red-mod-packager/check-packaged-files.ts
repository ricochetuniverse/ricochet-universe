import infinityDataFiles from '../../game-files/infinity-data-files.json';

import {type FileWithPath} from './generate-zip';

function getFileNameFromPath(path: string): string {
    return path.substring(path.lastIndexOf('/') + 1);
}

function isSoundFile(path: string): boolean {
    const pathLowercase = path.toLowerCase();
    return (
        pathLowercase.startsWith('sounds/') && pathLowercase.endsWith('.ogg')
    );
}

function getBaseGameSounds(): Map<string, string> {
    const baseGameSounds = new Map<string, string>();
    for (const path of infinityDataFiles) {
        if (!isSoundFile(path)) {
            continue;
        }

        baseGameSounds.set(getFileNameFromPath(path).toLowerCase(), path);
    }
    return baseGameSounds;
}

export default function checkPackagedFiles(files: FileWithPath[]): {
    sameFileNames: Map<string, string[]>;
    conflictWithBaseGame: Map<string, string>;
} {
    const groups = new Map<string, {fileName: string; paths: string[]}>();
    const conflictWithBaseGame = new Map<string, string>();

    const baseGameSounds = getBaseGameSounds();

    for (const file of files) {
        if (!isSoundFile(file.path)) {
            continue;
        }

        const fileName = getFileNameFromPath(file.path);
        const fileNameLowercase = fileName.toLowerCase();
        if (!groups.has(fileNameLowercase)) {
            groups.set(fileNameLowercase, {fileName, paths: []});
        }
        groups.get(fileNameLowercase)?.paths.push(file.path);

        // Check conflict with base game
        const baseGameSoundPath = baseGameSounds.get(fileNameLowercase);
        if (baseGameSoundPath) {
            conflictWithBaseGame.set(file.path, baseGameSoundPath);
        }
    }

    // Check sound files with same file name on different folders
    const sameFileNames = new Map<string, string[]>();
    for (const {fileName, paths} of groups.values()) {
        if (paths.length > 1) {
            sameFileNames.set(fileName, paths);
        }
    }

    return {
        sameFileNames,
        conflictWithBaseGame,
    };
}
