import * as styles from './DecompressorResultsImage.module.scss';

export type Appearance = 'BLACK' | 'WHITE' | 'CHECKERBOARD';

type Props = Readonly<{
    appearance: Appearance;
    className?: string;
    src: string;
}>;

export default function DecompressorResultsImage(props: Props) {
    return (
        <img
            src={props.src}
            alt=""
            className={
                (props.appearance === 'BLACK'
                    ? styles.backgroundBlack
                    : props.appearance === 'WHITE'
                      ? styles.backgroundWhite
                      : props.appearance === 'CHECKERBOARD'
                        ? styles.backgroundCheckerboard
                        : '') +
                ' ' +
                (props.className ?? '')
            }
        />
    );
}
