import {afterAll, afterEach, beforeAll, expect, rs, test} from '@rstest/core';
import {render, screen, waitFor} from '@testing-library/preact';
import {http, HttpResponse} from 'msw/http';

import DiscordWidgetContainer, {
    WIDGET_API_URL,
} from '../../../resources/js/discord/DiscordWidgetContainer';

import {server} from './mocks/node';

beforeAll(() => {
    server.listen();
});

afterEach(() => {
    server.resetHandlers();
    rs.restoreAllMocks();
});

afterAll(() => {
    server.close();
});

test('renders members', async () => {
    render(<DiscordWidgetContainer />);

    expect(await screen.findByText('100 Members Online')).not.toBeNull();

    const membersDiv = await screen.findByTestId('members');
    expect(membersDiv.childNodes).toHaveLength(1);
});

test('API failure', async () => {
    const consoleMock = rs.spyOn(console, 'error').mockImplementation(() => {});

    server.use(
        http.get(WIDGET_API_URL, () => {
            return new HttpResponse(null, {status: 500});
        })
    );

    render(<DiscordWidgetContainer />);

    await waitFor(() => {
        expect(consoleMock).toHaveBeenLastCalledWith(
            'Failed to load Discord members',
            new SyntaxError('Unexpected end of JSON input')
        );
    });

    expect(screen.queryByText('100 Members Online')).toBeNull();
});

test('network failure', async () => {
    const consoleMock = rs.spyOn(console, 'error').mockImplementation(() => {});

    server.use(
        http.get(WIDGET_API_URL, () => {
            return HttpResponse.error();
        })
    );

    render(<DiscordWidgetContainer />);

    await waitFor(() => {
        expect(consoleMock).toHaveBeenLastCalledWith(
            'Failed to load Discord members'
        );
    });

    expect(screen.queryByText('100 Members Online')).toBeNull();
});
