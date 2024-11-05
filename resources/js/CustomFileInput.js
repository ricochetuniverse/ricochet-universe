// @flow strict

import {h} from 'preact';
import {forwardRef} from 'preact/compat';

// Forked from https://github.com/reactstrap/reactstrap/blob/master/src/CustomFileInput.js

type Props = $ReadOnly<{
    label: string,
    directory?: boolean,

    type?: 'file',
    ...
}>;

function CustomFileInput(
    {label, directory = false, ...otherProps}: Props,
    ref: React$RefSetter<HTMLInputElement>
): React.Node {
    return (
        <div className="custom-file">
            {/* eslint-disable react/no-unknown-property */}
            <input
                type="file"
                {...otherProps}
                ref={ref}
                directory={directory ? directory : undefined}
                webkitdirectory={directory ? directory : undefined}
                allowdirs={directory ? directory : undefined}
                className="custom-file-input"
            />
            {/* eslint-enable react/no-unknown-property */}

            <label className="custom-file-label">
                {label ||
                    (directory ? 'Select a folder...' : 'Select a file...')}
            </label>
        </div>
    );
}

export default (forwardRef<Props, HTMLInputElement>(CustomFileInput): component(
    ref: React.RefSetter<HTMLInputElement>,
    ...Props
));
