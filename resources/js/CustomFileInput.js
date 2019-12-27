import {h} from 'preact';
import {forwardRef} from 'preact/compat';
import {Button} from 'reactstrap';

// Forked from https://github.com/reactstrap/reactstrap/blob/master/src/CustomFileInput.js

export default forwardRef(({label, directory, ...otherProps}, ref) => {
    return (
        <div className="custom-file">
            <input
                type="file"
                {...otherProps}
                ref={ref}
                directory={directory ? directory : undefined}
                webkitdirectory={directory ? directory : undefined}
                allowdirs={directory ? directory : undefined}
                className="custom-file-input"
            />

            <label className="custom-file-label">
                {label ||
                    (directory ? 'Select a folder...' : 'Select a file...')}
            </label>
        </div>
    );
});
