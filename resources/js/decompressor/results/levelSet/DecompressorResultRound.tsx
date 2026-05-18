import {useEffect, useMemo} from 'preact/hooks';
import Button from 'react-bootstrap/Button';

import type Round from '../../../level-set-parser/Round';
import type {RoundInfoType} from '../../../round-info/RoundInfoType';

type Props = Readonly<{
    count: number;
    round: Round;
    hideAuthorOutsideModal: boolean;
    setSelectedRound: (roundInfo: RoundInfoType) => void;
}>;

// See LevelRound.php -> shouldShowViewNotesButton()
function shouldShowViewNotesButton(round: Round) {
    if (
        round.notes[1] !== '' ||
        round.notes[2] !== '' ||
        round.notes[3] !== '' ||
        round.notes[4] !== ''
    ) {
        return true;
    }

    const common = [
        'http://www.ricochetinfinity.com',
        'http://www.ricochetinfinity.com/',
        'ricochetinfinity.com',
        'www.ricochetinfinity.com',
    ];

    return (
        round.notes[0] !== '' && !common.includes(round.notes[0].toLowerCase())
    );
}

export default function DecompressorResultRound({
    count,
    round,
    hideAuthorOutsideModal,
    setSelectedRound,
}: Props) {
    const imageSrc = useMemo(() => {
        if (!round.thumbnail) {
            return null;
        }

        const blob = new Blob([round.thumbnail as Uint8Array<ArrayBuffer>], {
            type: 'image/jpeg',
        });

        return URL.createObjectURL(blob);
    }, [round.thumbnail]);

    useEffect(() => {
        return () => {
            if (imageSrc) {
                URL.revokeObjectURL(imageSrc);
            }
        };
    }, [imageSrc]);

    return (
        <a
            href="#"
            className="roundInfo__link"
            onClick={(ev) => {
                ev.preventDefault();

                setSelectedRound({
                    name: round.name,
                    author: round.author,
                    note1: round.notes[0],
                    note2: round.notes[1],
                    note3: round.notes[2],
                    note4: round.notes[3],
                    note5: round.notes[4],
                    source: round.source,
                    imageUrl: imageSrc ?? undefined,
                });
            }}
        >
            {imageSrc ? (
                <img
                    src={imageSrc}
                    alt={`Screenshot of “${round.name}”`}
                    width="105"
                    height="80"
                    className="roundInfo__image"
                />
            ) : null}

            <span className="roundInfo__name">
                {count}: {round.name}
            </span>

            {!hideAuthorOutsideModal ? (
                <span className="roundInfo__author">
                    by {round.author !== '' ? round.author : <em>(not set)</em>}
                </span>
            ) : null}

            {shouldShowViewNotesButton(round) ? (
                <Button
                    variant="outline-primary"
                    className="d-block mt-2"
                    as="span"
                >
                    View notes
                </Button>
            ) : null}
        </a>
    );
}
