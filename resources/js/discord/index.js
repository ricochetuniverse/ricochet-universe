// noinspection ES6UnusedImports
import {h, render} from 'preact';
import DiscordWidgetContainer from './DiscordWidgetContainer';

const root = document.getElementById('discord-widget-root');

if (root) {
    render(<DiscordWidgetContainer />, root);
}
