import {
    fireEvent,
    render,
    screen,
    waitForElementToBeRemoved,
} from '@testing-library/react';

import RoundInfoModal from '../RoundInfoModal';

test('renders the modal', async () => {
    render(<RoundInfoModal launchTime={Date.now()} name="My great level" />);

    screen.getByText('My great level');

    fireEvent.click(screen.getByLabelText('Close'));
    await waitForElementToBeRemoved(() => screen.getByText('My great level'));
});
