import LevelSet from './LevelSet';
import Round from './Round';

import nullthrows from 'nullthrows';

type Nested = {
    key: string;
    value: string;
};

function setPropertyForLevelSet(
    levelSet: LevelSet,
    key: string,
    value: string
) {
    switch (key) {
        case 'Author':
            levelSet.author = value;
            break;

        case 'Description':
            levelSet.description = value;
            break;

        default:
            break;
    }
}

function setPropertyForRound(round: Round, key: string, value: string) {
    switch (key) {
        case 'Display Name':
            round.name = value;
            break;

        case 'Author':
            round.author = value;
            break;

        case 'Note 1':
            round.notes[0] = value;
            break;

        case 'Note 2':
            round.notes[1] = value;
            break;

        case 'Note 3':
            round.notes[2] = value;
            break;

        case 'Note 4':
            round.notes[3] = value;
            break;

        case 'Note 5':
            round.notes[4] = value;
            break;

        case 'Source':
            round.source = value;
            break;

        default:
            break;
    }
}

function decodeAsciiImage(buffer: Buffer): Buffer {
    const decodedBuffers: Buffer[] = [];

    for (let i = 0; i < buffer.length; i += 1) {
        const bytes = [buffer[i], buffer[i + 1]];

        if (bytes[0] === 9) {
            // tab, continue
            continue;
        }

        if (bytes[0] === 33) {
            if (bytes[1] === 35) {
                decodedBuffers.push(Buffer.from([255]));
                i += 1;
            } else if (bytes[1] === 36) {
                decodedBuffers.push(Buffer.from([123]));
                i += 1;
            } else if (bytes[1] === 37) {
                decodedBuffers.push(Buffer.from([125]));
                i += 1;
            } else {
                let continueChecking = true;
                for (let j = 38; j <= 70; j += 1) {
                    if (bytes[1] === j) {
                        decodedBuffers.push(Buffer.from([j - 38]));
                        i += 1;

                        continueChecking = false;
                        break;
                    }
                }

                if (continueChecking) {
                    if (bytes[1] === 34) {
                        decodedBuffers.push(Buffer.from([33]));
                        i += 1;
                    } else {
                        decodedBuffers.push(Buffer.from([bytes[1]]));
                    }
                }
            }
            continue;
        }

        decodedBuffers.push(Buffer.from([bytes[0]]));
    }

    return Buffer.concat(decodedBuffers);
}

export function parse(buffer: Buffer): LevelSet {
    if (buffer.indexOf('CRoundSetUserMade') !== 0) {
        throw new Error(
            'Level sets should start with CRoundSetUserMade as the first line'
        );
    }

    const levelSet = new LevelSet();

    const nested: Nested[] = [];
    let previousKey = '';
    let previousValue = '';
    let currentWorkingRound: Round | null = null;
    let currentWorkingRoundPictureBuffers: Buffer[] = [];

    let byteStartOffset = 0;
    let byteEndOffset = buffer.indexOf('\r\n');
    while (byteEndOffset !== -1) {
        const line = buffer
            .toString(
                'utf8', // might need to convert??
                byteStartOffset,
                byteEndOffset
            )
            .replace(/^\t+/, '');

        if (line === '{') {
            nested.push({key: previousKey, value: previousValue});

            if (previousKey === 'Round') {
                currentWorkingRound = new Round();
            } else if (previousKey === 'Compressed Thumbnail') {
                currentWorkingRoundPictureBuffers = [];
            }
        } else if (line === '}') {
            const popped = nested.pop();

            if (popped) {
                if (popped.key === 'Round') {
                    levelSet.rounds.push(
                        nullthrows(
                            currentWorkingRound,
                            'Expected current working round'
                        )
                    );
                    currentWorkingRound = null;
                } else if (popped.key === 'Compressed Thumbnail') {
                    const lastNested = nested[nested.length - 1];

                    if (lastNested.key === 'Round') {
                        nullthrows(
                            currentWorkingRound,
                            'Expected current working round'
                        ).thumbnail = decodeAsciiImage(
                            Buffer.concat(currentWorkingRoundPictureBuffers)
                        );
                        currentWorkingRoundPictureBuffers = [];
                    }
                }
            }
        } else {
            const split = line.indexOf('=');
            const key = line.substring(0, split);
            const value = line.substring(split + 1);

            if (nested.length) {
                const lastNested = nested[nested.length - 1];

                if (lastNested.key === 'Compressed Thumbnail') {
                    // Collect all the strings to concat them in the end
                    previousKey = '';

                    currentWorkingRoundPictureBuffers.push(
                        buffer.slice(byteStartOffset, byteEndOffset)
                    );
                } else {
                    if (lastNested.value === 'CRoundSetUserMade') {
                        setPropertyForLevelSet(levelSet, key, value);
                    } else if (lastNested.key === 'Round') {
                        setPropertyForRound(
                            nullthrows(
                                currentWorkingRound,
                                'Expected current working round'
                            ),
                            key,
                            value
                        );
                    }
                }
            }

            previousKey = key;
            previousValue = value;
        }

        byteStartOffset = byteEndOffset + 2;
        byteEndOffset = buffer.indexOf('\r\n', byteStartOffset);
    }

    return levelSet;
}
