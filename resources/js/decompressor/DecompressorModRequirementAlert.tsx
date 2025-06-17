import {useMemo} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';

import type {ModRequirement} from './check-for-mods';
import checkForMods from './check-for-mods';

type Props = Readonly<{
    textResult: string;
}>;

export default function DecompressorModRequirementAlert({textResult}: Props) {
    const modRequirement = useMemo<ModRequirement>(() => {
        return textResult !== '' ? checkForMods(textResult) : {result: false};
    }, [textResult]);

    if (!modRequirement.result) {
        return null;
    }

    return (
        <Alert variant="info">
            {modRequirement.mods.length >= 2 ? (
                <>
                    This level set requires these mods to play:{' '}
                    <a href="/mods" className="alert-link">
                        {modRequirement.mods.join(', ')}
                    </a>
                </>
            ) : modRequirement.mods.length === 1 ? (
                <>
                    This level set requires the{' '}
                    <a href="/mods" className="alert-link">
                        {modRequirement.mods[0]} mod
                    </a>{' '}
                    to play.
                </>
            ) : (
                'This level set requires files that are not available on the base game.'
            )}
        </Alert>
    );
}
