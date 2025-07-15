import type {z} from 'zod/mini';

import DiscordWidgetMember from './DiscordWidgetMember';
import {DiscordWidgetMemberSchema} from './DiscordWidgetMemberType';

type Props = Readonly<{
    loading: boolean;
    error: boolean;
    members: z.infer<typeof DiscordWidgetMemberSchema>[];
    presenceCount: number;
}>;

export default function DiscordWidget(props: Props) {
    if (props.loading) {
        return <div className="discordWidget__body">Loading...</div>;
    }

    if (props.error) {
        // Just show nothing, at least there's a Join button
        return null;
    }

    return (
        <div className="discordWidget__body">
            <div className="discordWidget__heading">
                {props.presenceCount} Members Online
            </div>

            <ul className="discordWidget__memberList" data-testid="members">
                {props.members.map((member) => {
                    return (
                        <DiscordWidgetMember key={member.id} member={member} />
                    );
                })}
            </ul>
        </div>
    );
}
