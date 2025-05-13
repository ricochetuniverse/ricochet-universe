import {render} from 'preact';
import {z} from 'zod';

import RoundInfoModal from './RoundInfoModal';

const schema = z
    .object({
        name: z.string(),
        author: z.string(),
        note1: z.string(),
        note2: z.string(),
        note3: z.string(),
        note4: z.string(),
        note5: z.string(),
        source: z.string(),
        imageUrl: z.string().url(),
    })
    .partial();

let modalWrap: HTMLDivElement | null;

const links = document.getElementsByClassName('roundInfo__link');
for (let i = 0, len = links.length; i < len; i += 1) {
    links[i].addEventListener('click', (ev) => {
        ev.preventDefault();

        const link = ev.currentTarget;
        if (!(link instanceof HTMLElement)) {
            return;
        }

        const raw = link.dataset.roundInfo;
        if (raw == null) {
            return;
        }

        const roundInfo = schema.parse(JSON.parse(raw));

        if (!modalWrap) {
            modalWrap = document.createElement('div');
            document.body.appendChild(modalWrap);
        }

        render(
            <RoundInfoModal launchTime={Date.now()} {...roundInfo} />,
            modalWrap
        );
    });
}
