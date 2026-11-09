import {assert, expect, test} from '@rstest/core';

import type {DataBrowserListingType} from '../../../resources/js/data-browser/DataBrowserListing';
import parsePath from '../../../resources/js/data-browser/parsePath';

const ALL_DIRECTORIES_LISTINGS: DataBrowserListingType = [
    {
        path: '/',
        directories: ['Cache'],
        files: ['Affiliate.txt', 'Credits.txt'],
    },
    {
        path: 'Cache/',
        directories: ['Interface', 'Resources'],
        files: [],
    },
    {
        path: 'Cache/Resources/',
        directories: ['Achievements'],
        files: ['!None.Frame', '!None.Sequence'],
    },
];

test('top-level directory', () => {
    const parsed = parsePath('/', ALL_DIRECTORIES_LISTINGS);

    assert(parsed != null);
    expect(parsed.dirname).toBe('/');
    assert(!('filename' in parsed));
    expect(parsed.list.directories).toStrictEqual(['Cache']);
    expect(parsed.list.files).toStrictEqual(['Affiliate.txt', 'Credits.txt']);
});

test('a filename at the top-level directory', () => {
    const parsed = parsePath('Credits.txt', ALL_DIRECTORIES_LISTINGS);

    assert(parsed != null);
    expect(parsed.dirname).toBe('/');
    assert('filename' in parsed);
    expect(parsed.filename).toBe('Credits.txt');
    expect('list' in parsed).toBe(false);
});

test('inside a directory without slash', () => {
    const parsed = parsePath('Cache', ALL_DIRECTORIES_LISTINGS);

    assert(parsed != null);
    expect(parsed.dirname).toBe('Cache/');
    assert(!('filename' in parsed));
    expect(parsed.list.directories).toStrictEqual(['Interface', 'Resources']);
    expect(parsed.list.files).toHaveLength(0);
});

test('inside a directory with slash', () => {
    const parsed = parsePath('Cache/', ALL_DIRECTORIES_LISTINGS);

    assert(parsed != null);
    expect(parsed.dirname).toBe('Cache/');
    assert(!('filename' in parsed));
    expect(parsed.list.directories).toStrictEqual(['Interface', 'Resources']);
    expect(parsed.list.files).toHaveLength(0);
});

test('a filename inside a directory', () => {
    const parsed = parsePath(
        'Cache/Resources/!None.Sequence',
        ALL_DIRECTORIES_LISTINGS
    );

    assert(parsed != null);
    expect(parsed.dirname).toBe('Cache/Resources/');
    assert('filename' in parsed);
    expect(parsed.filename).toBe('!None.Sequence');
    expect('list' in parsed).toBe(false);
});

test('does not exist', () => {
    const parsed = parsePath('nothing', ALL_DIRECTORIES_LISTINGS);

    expect(parsed).toBeNull();
});
