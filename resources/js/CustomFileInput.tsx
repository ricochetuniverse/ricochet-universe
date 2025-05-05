import preact from 'preact';
import {forwardRef} from 'preact/compat';

// Forked from https://github.com/reactstrap/reactstrap/blob/master/src/CustomFileInput.js

type Props = Readonly<
    {
        label: string;
        directory?: boolean;
        type?: 'file';
    } & preact.JSX.InputHTMLAttributes<HTMLInputElement>
>;

function CustomFileInput(
    {label, directory = false, ...otherProps}: Props,
    ref: preact.Ref<HTMLInputElement>
) {
    return (
        <div className="custom-file">
            <input
                type="file"
                {...otherProps}
                ref={ref}
                // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/webkitdirectory
                webkitdirectory={directory ? directory : undefined}
                className="custom-file-input"
            />

            <label className="custom-file-label">
                {label ||
                    (directory ? 'Select a folder...' : 'Select a file...')}
            </label>
        </div>
    );
}

export default forwardRef<HTMLInputElement, Props>(CustomFileInput);
