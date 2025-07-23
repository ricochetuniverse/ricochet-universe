import gameData from '../../game-files/infinity-data-files.json';

const fileNameLowercaseToPathCache = new Map<string, string>();
const pathLowercaseCache = new Set<string>();

export function getFileNameFromPath(path: string): string {
    return path.substring(path.lastIndexOf('/') + 1);
}

export function findFileNameInAnyFolderInGameData(
    fileName: string
): string | undefined {
    if (fileNameLowercaseToPathCache.size === 0) {
        for (const gamePath of gameData) {
            fileNameLowercaseToPathCache.set(
                getFileNameFromPath(gamePath).toLowerCase(),
                gamePath
            );
        }
    }

    return fileNameLowercaseToPathCache.get(fileName.toLowerCase());
}

export function pathExistsInGameData(path: string): boolean {
    if (pathLowercaseCache.size === 0) {
        for (const gamePath of gameData) {
            pathLowercaseCache.add(gamePath.toLowerCase());
        }
    }

    return pathLowercaseCache.has(path.toLowerCase());
}
