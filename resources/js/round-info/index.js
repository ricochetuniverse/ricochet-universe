import $ from 'jquery';
// noinspection ES6UnusedImports
import {h, render} from 'preact';

import RoundInfoModal from './RoundInfoModal';

let modalWrap;

$('.roundInfo__link').on('click', function(ev) {
    ev.preventDefault();

    const $link = $(this);

    const props = {
        name: $link.data('round-name'),
        author: $link.data('round-author'),
        note1: $link.data('round-note-1'),
        note2: $link.data('round-note-2'),
        note3: $link.data('round-note-3'),
        note4: $link.data('round-note-4'),
        note5: $link.data('round-note-5'),
        source: $link.data('round-source'),
        imageUrl: $link.data('round-image-url'),
    };

    if (!modalWrap) {
        modalWrap = document.createElement('div');
        document.body.appendChild(modalWrap);
    }

    render(
        <RoundInfoModal {...props} />,
        modalWrap,
        modalWrap.lastElementChild
    );
});
