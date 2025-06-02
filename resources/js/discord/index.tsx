import {render} from 'preact';

import DiscordWidgetContainer from './DiscordWidgetContainer';

const root = document.querySelector('.discordWidget__reactWrap');

if (root) {
    if (window.IntersectionObserver) {
        const observer = new IntersectionObserver(
            (entries) => {
                if (entries[0].intersectionRatio > 0) {
                    observer.disconnect();

                    render(<DiscordWidgetContainer />, root);
                }
            },
            {rootMargin: '0px 0px 500px 0px'}
        );

        observer.observe(root);
    } else {
        render(<DiscordWidgetContainer />, root);
    }
}
