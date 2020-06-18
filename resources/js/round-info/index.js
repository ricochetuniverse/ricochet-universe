// @flow strict

import nullthrows from 'nullthrows';
import {h, render} from 'preact';

import RoundInfoModal from './RoundInfoModal';

let modalWrap;
let currentOpenCount = 0;

const links = document.getElementsByClassName('roundInfo__link');
for (let i = 0, len = links.length; i < len; i += 1) {
    links[i].addEventListener('click', (ev: MouseEvent) => {
        ev.preventDefault();

        const link = ev.currentTarget;
        if (!(link instanceof HTMLElement)) {
            return;
        }

        const props = {
            name: link.dataset['roundName'],
            author: link.dataset['roundAuthor'],
            note1: link.dataset['roundNote-1'],
            note2: link.dataset['roundNote-2'],
            note3: link.dataset['roundNote-3'],
            note4: link.dataset['roundNote-4'],
            note5: link.dataset['roundNote-5'],
            source: link.dataset['roundSource'],
            imageUrl: link.dataset['roundImageUrl'],
        };

        if (!modalWrap) {
            modalWrap = document.createElement('div');
            nullthrows(document.body).appendChild(modalWrap);
        }

        currentOpenCount += 1;

        render(
            <RoundInfoModal currentOpenCount={currentOpenCount} {...props} />,
            modalWrap
        );
    });
}
