export default function getDiscordMemberName(user) {
    if (user.nick != null) {
        return user.nick;
    }

    return user.username;
}
