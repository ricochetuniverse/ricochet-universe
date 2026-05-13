type Props = Readonly<{
    appearance: string;
    base64: string;
    className?: string;
}>;

export default function Image(props: Props) {
    return (
        <img
            src={'data:image/png;base64,' + props.base64}
            alt=""
            className={
                'decompressor__image--' +
                props.appearance +
                ' ' +
                (props.className ?? '')
            }
        />
    );
}
