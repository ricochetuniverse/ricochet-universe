import {render} from 'preact';

import RoundInfoModal from './RoundInfoModal';
import {RoundInfoSchema} from './RoundInfoType';

let modalWrap: HTMLDivElement | null;

const links = document.getElementsByClassName('js-open-round-info-modal');
for (let i = 0, len = links.length; i < len; i += 1) {
    links[i].addEventListener('click', (ev) => {
        ev.preventDefault();

        const link = ev.currentTarget;
        if (!(link instanceof HTMLElement)) {
            return;
        }

        const raw = link.dataset.roundInfo;
        if (raw == null) {
            throw new Error('No round info found.');
        }

        const roundInfo = RoundInfoSchema.parse(JSON.parse(raw));

        if (!modalWrap) {
            modalWrap = document.createElement('div');
            document.body.appendChild(modalWrap);
        }

        render(
            <RoundInfoModal launchTime={Date.now()} roundInfo={roundInfo} />,
            modalWrap
        );
    });
}
