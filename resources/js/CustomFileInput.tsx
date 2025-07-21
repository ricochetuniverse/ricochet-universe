import {forwardRef} from 'preact/compat';

type Props = Readonly<
    Omit<React.ComponentProps<'input'>, 'type' | 'className'> & {
        label?: string;
        directory?: boolean;
    }
>;

function CustomFileInput(
    {label, directory = false, ...otherProps}: Props,
    ref: React.Ref<HTMLInputElement>
) {
    const formLabel = label || (directory ? 'Select a folder:' : '');

    return (
        <div className="d-flex align-items-center">
            {formLabel !== '' ? (
                <span className="flex-shrink-0 me-2">{formLabel}</span>
            ) : null}

            <input
                {...otherProps}
                className="form-control"
                ref={ref}
                type="file"
                // @ts-expect-error https://developer.mozilla.org/en-US/docs/Web/API/HTMLInputElement/webkitdirectory
                webkitdirectory={directory ? 'true' : undefined}
            />
        </div>
    );
}

export default forwardRef<HTMLInputElement, Props>(CustomFileInput);
