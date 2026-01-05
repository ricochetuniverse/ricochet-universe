import {useEffect, useState} from 'preact/hooks';

type Props = Readonly<{
    text?: string;
}>;
export default function SuspenseComponentLoadingText({
    text = 'Loading...',
}: Props) {
    const [longTime, setLongTime] = useState(false);

    useEffect(() => {
        const timer = window.setTimeout(() => {
            setLongTime(true);
        }, 10000);

        return () => {
            window.clearTimeout(timer);
        };
    }, []);

    return text + (longTime ? ' this is taking longer than expected...' : '');
}
