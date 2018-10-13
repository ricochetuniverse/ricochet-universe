// noinspection ES6UnusedImports
import {Component, h} from 'preact';

import DiscordWidget from './DiscordWidget';
import getDiscordMemberName from './getDiscordMemberName';

export default class DiscordWidgetContainer extends Component {
    state = {
        loading: true,
        error: false,

        members: [],
    };

    render() {
        return (
            <DiscordWidget
                loading={this.state.loading}
                error={this.state.error}
                members={this.state.members}
                inviteLink="https://discord.gg/fKK42Wt"
            />
        );
    }

    componentDidMount() {
        const request = new XMLHttpRequest();

        // The extra URL parameters aren't used by Discord, but it's to ensure we get proper CORS headers, not cached
        // from another origin
        request.open(
            'GET',
            'https://discordapp.com/api/guilds/295184393109110785/widget.json?_=' + window.location.origin,
            true
        );
        request.onload = () => {
            let json;
            try {
                json = JSON.parse(request.responseText);
            } catch (ex) {
                console.error(ex);
                this.setState({
                    loading: false,
                    error: true,
                });

                return;
            }

            const members = json.members
                .filter((member) => {
                    const bots = [
                        '270904126974590976', // Dank Memer
                        '159985870458322944', // Mee6
                        '439205512425504771', // NotSoBot
                        '235088799074484224', // Rythm
                    ];

                    return bots.indexOf(member.id) === -1;
                })
                .sort((a, b) => {
                    const nameA = getDiscordMemberName(a).toLowerCase();
                    const nameB = getDiscordMemberName(b).toLowerCase();

                    if (nameA > nameB) {
                        return 1;
                    } else if (nameA < nameB) {
                        return -1;
                    }

                    return 0;
                });

            this.setState({
                loading: false,
                members,
            });
        };
        request.onerror = () => {
            this.setState({
                loading: false,
                error: true,
            });
        };
        request.send();
    }
}
