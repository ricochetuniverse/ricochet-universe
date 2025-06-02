import {useEffect, useState} from 'preact/hooks';
import type {z} from 'zod/v4-mini';

import DiscordWidget from './DiscordWidget';
import {
    DiscordWidgetApiSchema,
    type DiscordWidgetMemberSchema,
} from './DiscordWidgetMemberType';

export const WIDGET_API_URL =
    'https://discordapp.com/api/guilds/295184393109110785/widget.json';

const BOTS = [
    'AmariBot',
    '[pls] Dank Memer',
    '[!] Mee6',
    '[.] NotSoBot',
    '[r!] Rythm',
];

export default function DiscordWidgetContainer() {
    const [isLoading, setIsLoading] = useState(true);
    const [isError, setIsError] = useState(false);
    const [members, setMembers] = useState<
        z.infer<typeof DiscordWidgetMemberSchema>[]
    >([]);
    const [presenceCount, setPresenceCount] = useState(0);

    useEffect(() => {
        const request = new XMLHttpRequest();

        // Cache-bust to ensure we get proper CORS headers, not cached from another origin
        request.open(
            'GET',
            WIDGET_API_URL + '?_=' + Date.now().toString(),
            true
        );
        request.onload = () => {
            let json;
            try {
                json = DiscordWidgetApiSchema.parse(
                    JSON.parse(request.responseText)
                );
            } catch (ex) {
                console.error('Failed to load Discord members', ex);

                setIsLoading(false);
                setIsError(true);
                return;
            }

            const members = json.members
                .filter((member) => {
                    return !BOTS.includes(member.username);
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
            setPresenceCount(Math.max(0, json.presence_count - BOTS.length));
        };
        request.onerror = () => {
            console.error('Failed to load Discord members');

            setIsLoading(false);
            setIsError(true);
        };
        request.send();

        return () => {
            request.abort();
        };
    }, []);

    return (
        <DiscordWidget
            loading={isLoading}
            error={isError}
            members={members}
            presenceCount={presenceCount}
        />
    );
}
