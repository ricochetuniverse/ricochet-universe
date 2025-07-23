import assert from 'node:assert/strict';
import fs from 'node:fs/promises';
import path from 'node:path';

import {expect, jest, test} from '@jest/globals';
import {render, screen} from '@testing-library/preact';
import userEvent from '@testing-library/user-event';

import RedModPackagerApp from '../../../resources/js/red-mod-packager/RedModPackagerApp';

import getTestFile from './getTestFile';

const FIXTURE_DIR = path.resolve(__dirname, '../../fixtures/');

beforeAll(() => {
    // called by uppie, unused
    jest.spyOn(FormData.prototype, 'append').mockImplementation(() => {});
});

afterAll(() => {
    jest.restoreAllMocks();
});

test('renders the app', async () => {
    render(<RedModPackagerApp />);

    expect(await screen.findByText('RED mod packager')).not.toBeNull();
});

test('makes RED packages', async () => {
    const user = userEvent.setup();
    render(<RedModPackagerApp />);

    const sequence = await getTestFile();
    const input = screen.getByTestId('file');
    await user.upload(input, [sequence]);

    expect(
        await screen.findAllByText(
            'Cache/Resources/Player Ship/Player Shot.Sequence'
        )
    ).not.toBeNull();

    // Download the packaged file
    const downloadButton = screen.getByText('Download My Mod.red');
    expect(downloadButton).toBeInstanceOf(HTMLAnchorElement);
    assert(downloadButton instanceof HTMLAnchorElement);

    const packaged = await fetch(downloadButton.href).then((r) => {
        return r.arrayBuffer();
    });

    // Compare
    const existing = await fs.readFile(
        path.resolve(FIXTURE_DIR, './red-mod-packager/packaged.red')
    );

    expect(packaged).toStrictEqual(existing.buffer);
});
