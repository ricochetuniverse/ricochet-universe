// @flow strict

import {h} from 'preact';
import {useEffect, useState} from 'preact/hooks';

import DiscordWidget from './DiscordWidget';

import type {DiscordWidgetMemberType} from './DiscordWidgetMemberType';

export default function DiscordWidgetContainer(): React.Node {
    const [isLoading, setIsLoading] = useState(true);
    const [isError, setIsError] = useState(false);
    const [members, setMembers] = useState<DiscordWidgetMemberType[]>([]);

    useEffect(() => {
        const request = new XMLHttpRequest();

        // Cache-bust to ensure we get proper CORS headers, not cached from another origin
        request.open(
            'GET',
            'https://discordapp.com/api/guilds/295184393109110785/widget.json?_=' +
                Date.now(),
            true
        );
        request.onload = () => {
            let json: {...};
            try {
                json = JSON.parse(request.responseText);
            } catch (ex) {
                console.error(ex);

                setIsLoading(false);
                setIsError(true);

                return;
            }

            const members = (json.members: DiscordWidgetMemberType[])
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

            setIsLoading(false);
            setMembers(members);
        };
        request.onerror = () => {
            setIsLoading(false);
            setIsError(true);
        };
        request.send();

        return () => {
            request.abort();
        };
    }, []);

    return (
        <DiscordWidget loading={isLoading} error={isError} members={members} />
    );
}
