import {
    fireEvent,
    render,
    screen,
    waitForElementToBeRemoved,
} from '@testing-library/preact';
import {expect} from 'expect';

import RoundInfoModal from '../RoundInfoModal';

test('renders the modal', async () => {
    render(<RoundInfoModal launchTime={Date.now()} name="My great level" />);

    const heading = await screen.findByRole('heading');
    expect(heading.textContent).toEqual('My great level');

    fireEvent.click(screen.getByLabelText('Close'));
    await waitForElementToBeRemoved(() => screen.queryByText('My great level'));
});
