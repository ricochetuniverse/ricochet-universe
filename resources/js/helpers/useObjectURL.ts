import {useState} from 'preact/hooks';

export default function useObjectURL(currentUrl: string | null) {
    const [prevUrl, setPrevUrl] = useState(currentUrl);
    if (currentUrl !== prevUrl) {
        if (prevUrl != null) {
            window.URL.revokeObjectURL(prevUrl);
        }

        setPrevUrl(currentUrl);
    }
}
