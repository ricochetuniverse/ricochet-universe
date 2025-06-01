import type {z} from 'zod/v4-mini';

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

            <ul className="discordWidget__memberList">
                {props.members.map((member) => {
                    return (
                        <li className="discordWidget__member" key={member.id}>
                            <div className="discordWidget__member__avatar">
                                <img
                                    src={member.avatar_url}
                                    alt={member.username + '’s avatar'}
                                    width={16}
                                    height={16}
                                    className="discordWidget__member__avatar__image"
                                    loading="lazy"
                                />
                                <span
                                    className={
                                        'discordWidget__member__avatar__status discordMemberStatus--' +
                                        member.status
                                    }
                                />
                            </div>
                            <span className="text-truncate">
                                {member.username}
                            </span>
                        </li>
                    );
                })}
            </ul>
        </div>
    );
}
