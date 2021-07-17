// @flow strict

import LevelSet from './LevelSet';
import Round from './Round';

import nullthrows from 'nullthrows';

type Nested = {|
    key: string,
    value: string,
|};

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

export function parse(text: string): LevelSet {
    if (!text.startsWith('CRoundSetUserMade')) {
        throw new Error(
            'Level sets should start with CRoundSetUserMade as the first line'
        );
    }

    const levelSet = new LevelSet();

    const nested: Nested[] = [];
    let previousKey = '';
    let previousValue = '';
    let currentWorkingRound: ?Round = null;

    const lines = text.split('\r\n');
    for (let i = 0; i < lines.length; i += 1) {
        const line = lines[i].replace(/^\t+/, '');

        if (line === '{') {
            nested.push({key: previousKey, value: previousValue});

            if (previousKey === 'Round') {
                currentWorkingRound = new Round();
            }
        } else if (line === '}') {
            const popped = nested.pop();

            if (popped.key === 'Round') {
                levelSet.rounds.push(
                    nullthrows(
                        currentWorkingRound,
                        'Expected current working round'
                    )
                );
                currentWorkingRound = null;
            }
        } else {
            const split = line.split('=');
            const key = split[0];
            const value = split.slice(1).join('=');

            if (nested.length) {
                const lastNested = nested[nested.length - 1];

                switch (lastNested.key) {
                    case 'CRoundSetUserMade':
                        setPropertyForLevelSet(levelSet, key, value);
                        break;

                    case 'Round':
                        setPropertyForRound(
                            nullthrows(
                                currentWorkingRound,
                                'Expected current working round'
                            ),
                            key,
                            value
                        );
                        break;

                    default:
                        break;
                }
            }

            previousKey = key;
            previousValue = value;
        }
    }

    return levelSet;
}
