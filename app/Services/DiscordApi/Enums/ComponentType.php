<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Enums;

/**
 * @see https://discord.com/developers/docs/components/reference#component-object-component-types
 */
enum ComponentType: int
{
    /** Container to display a row of interactive components */
    case ACTION_ROW = 1;

    /** Button object */
    case BUTTON = 2;

    /** Select menu for picking from defined text options */
    case STRING_SELECT = 3;

    /** Text input object */
    case TEXT_INPUT = 4;

    /** Select menu for users */
    case USER_SELECT = 5;

    /** Select menu for roles */
    case ROLE_SELECT = 6;

    /** Select menu for mentionables (users and roles) */
    case MENTIONABLE_SELECT = 7;

    /** Select menu for channels */
    case CHANNEL_SELECT = 8;

    /** Container to display text alongside an accessory component */
    case SECTION = 9;

    /** Markdown text */
    case TEXT_DISPLAY = 10;

    /** Small image that can be used as an accessory */
    case THUMBNAIL = 11;

    /** Display images and other media */
    case MEDIA_GALLERY = 12;

    /** Displays an attached file */
    case FILE = 13;

    /** Component to add vertical padding between other components */
    case SEPARATOR = 14;

    /** Container that visually groups a set of components */
    case CONTAINER = 17;

    /** Container associating a label and description with a component */
    case LABEL = 18;
}
