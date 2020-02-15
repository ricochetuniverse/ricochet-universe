// @flow

import {h, render} from 'preact';
import DiscordWidgetContainer from './DiscordWidgetContainer';

const root = document.querySelector('.discordWidget__reactWrap');

if (root) {
    render(<DiscordWidgetContainer />, root);
}
