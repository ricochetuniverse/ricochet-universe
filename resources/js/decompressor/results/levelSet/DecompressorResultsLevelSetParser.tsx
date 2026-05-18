import {Buffer} from 'buffer';

import {useMemo, useState} from 'preact/hooks';
import Card from 'react-bootstrap/Card';

import type LevelSet from '../../../level-set-parser/LevelSet';
import {parse} from '../../../level-set-parser/LevelSetParser';
import RoundInfoModal from '../../../round-info/RoundInfoModal';
import type {RoundInfoType} from '../../../round-info/RoundInfoType';

import DecompressorResultRound from './DecompressorResultRound';

type Props = Readonly<{
    raw: Uint8Array<ArrayBuffer>;
}>;

function buildAuthorSearchUrl(author: string) {
    const url = new URL('/levels/index.php', window.location.href);
    url.searchParams.set('author', author);
    return url.toString();
}

function isAuthorIsSameForAllRounds(levelSet: LevelSet) {
    let roundsWithSameAuthor = 0;
    let roundsWithNullAuthor = 0;

    levelSet.rounds.forEach((round) => {
        if (round.author === levelSet.author) {
            roundsWithSameAuthor += 1;
        } else if (round.author === '') {
            roundsWithNullAuthor += 1;
        }
    });

    return (
        roundsWithSameAuthor === levelSet.rounds.length ||
        roundsWithNullAuthor === levelSet.rounds.length
    );
}

export default function DecompressorResultsLevelSetParser(props: Props) {
    const [selectedRound, setSelectedRound] = useState<RoundInfoType | null>(
        null
    );
    const [modalOpen, setModalOpen] = useState(0);

    const levelSet = useMemo(() => {
        try {
            return parse(Buffer.from(props.raw.buffer));
        } catch {
            return null;
        }
    }, [props.raw]);

    if (!levelSet) {
        return null;
    }

    const hideAuthorOutsideModal = isAuthorIsSameForAllRounds(levelSet);

    return (
        <Card as="section">
            <Card.Header as="h2">Decompressed level set</Card.Header>

            <Card.Body>
                {levelSet.author !== '' || levelSet.description !== '' ? (
                    <div className="d-table mb-3">
                        {levelSet.author !== '' ? (
                            <div className="d-table-row">
                                <div className="d-table-cell pe-2">Author:</div>
                                <div className="d-table-cell">
                                    {levelSet.author}{' '}
                                    <a
                                        href={buildAuthorSearchUrl(
                                            levelSet.author
                                        )}
                                        title={`Find level sets created by ${levelSet.author}`}
                                    >
                                        (search levels)
                                    </a>
                                </div>
                            </div>
                        ) : null}

                        {levelSet.description !== '' ? (
                            <div className="d-table-row">
                                <div className="d-table-cell pe-2">
                                    Description:
                                </div>
                                <div className="d-table-cell cursor-auto">
                                    {levelSet.description}
                                </div>
                            </div>
                        ) : null}
                    </div>
                ) : null}

                <div className="roundInfo__wrapper">
                    {levelSet.rounds.map((round, index) => {
                        return (
                            <DecompressorResultRound
                                // eslint-disable-next-line @eslint-react/no-array-index-key
                                key={index}
                                count={index + 1}
                                round={round}
                                hideAuthorOutsideModal={hideAuthorOutsideModal}
                                setSelectedRound={(round) => {
                                    setSelectedRound(round);
                                    setModalOpen(Date.now());
                                }}
                            />
                        );
                    })}
                </div>
            </Card.Body>

            {selectedRound ? (
                <RoundInfoModal key={modalOpen} roundInfo={selectedRound} />
            ) : null}
        </Card>
    );
}
