import {useMemo} from 'preact/hooks';
import Alert from 'react-bootstrap/Alert';

import checkPackagedFiles from './check-packaged-files';
import {type FileWithPath} from './generate-zip';

type Props = Readonly<{
    files: FileWithPath[];
}>;

export default function RedModPackagerFilesCheck({files}: Props) {
    const result = useMemo(() => {
        return checkPackagedFiles(files);
    }, [files]);

    const warnings = [];
    if (result.sameFileNames.size > 0) {
        warnings.push(
            <Alert variant="warning">
                You have sound files that share the same file name. Due to a
                game engine bug, this will not work properly in-game as the game
                treats <code>a/abc.ogg</code> and <code>b/abc.ogg</code> as the
                same sound even if you placed them on different folders.
                <br />
                <br />
                Conflicting sound files:
                <ul className="m-0">
                    {Array.from(result.sameFileNames.entries()).map(
                        ([fileName, paths]) => {
                            return (
                                <li key={fileName}>
                                    {fileName}
                                    <ul>
                                        {paths.map((path) => {
                                            return <li key={path}>{path}</li>;
                                        })}
                                    </ul>
                                </li>
                            );
                        }
                    )}
                </ul>
            </Alert>
        );
    }

    if (result.conflictWithBaseGame.size > 0) {
        warnings.push(
            <Alert variant="warning">
                You have sound files that share the same file name as the base
                game. Due to a game engine bug, this will{' '}
                <strong>overwrite the original sound</strong> even if you placed
                your sounds on different folders. Please rename the sounds if
                you didn’t intend to overwrite the base game sounds.
                <br />
                <br />
                Sound files that would be overwritten:
                <ul className="m-0">
                    {Array.from(result.conflictWithBaseGame.entries()).map(
                        ([fileName, path]) => {
                            return (
                                <li key={fileName}>
                                    {fileName} → {path}
                                </li>
                            );
                        }
                    )}
                </ul>
            </Alert>
        );
    }

    return warnings;
}
