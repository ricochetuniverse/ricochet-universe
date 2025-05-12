import preact from 'preact';
import {forwardRef} from 'preact/compat';

// Forked from https://github.com/reactstrap/reactstrap/blob/master/src/CustomFileInput.js

type Props = Readonly<
    {
        label?: string;
        directory?: boolean;
        type?: 'file';
    } & preact.JSX.InputHTMLAttributes<HTMLInputElement>
>;

function CustomFileInput(
    {label, directory = false, ...otherProps}: Props,
    ref: preact.Ref<HTMLInputElement>
) {
    const formLabel = label || (directory ? 'Select a folder:' : '');

    return (
        <div className="d-flex align-items-center">
            {formLabel !== '' ? (
                <span className="flex-shrink-0 me-2">{formLabel}</span>
            ) : null}

            <input
                type="file"
                {...otherProps}
                ref={ref}
                // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/webkitdirectory
                webkitdirectory={directory ? directory : undefined}
                className="form-control"
            />
        </div>
    );
}

export default forwardRef<HTMLInputElement, Props>(CustomFileInput);
