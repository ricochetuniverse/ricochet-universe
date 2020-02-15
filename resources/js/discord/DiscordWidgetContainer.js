import {Component, h} from 'preact';

import DiscordWidget from './DiscordWidget';

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
            />
        );
    }

    componentDidMount() {
        const request = new XMLHttpRequest();

        // Cache-bust to ensure we get proper CORS headers, not cached from another origin
        request.open(
            'GET',
            'https://discordapp.com/api/guilds/295184393109110785/widget.json?_=' +
                Date.now(),
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
                        '[pls] Dank Memer',
                        '[!] Mee6',
                        '[.] NotSoBot',
                        '[r!] Rythm',
                    ];

                    return bots.indexOf(member.username) === -1;
                })
                .sort((a, b) => {
                    const nameA = a.username.toLowerCase();
                    const nameB = b.username.toLowerCase();

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
