// @flow strict

import {h} from 'preact';

import type {DiscordWidgetMemberType} from './DiscordWidgetMemberType';

type Props = $ReadOnly<{|
    loading: boolean,
    error: boolean,
    members: DiscordWidgetMemberType[],
|}>;

export default function DiscordWidget(props: Props): React.Node {
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
                {props.members.length} Members Online
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
