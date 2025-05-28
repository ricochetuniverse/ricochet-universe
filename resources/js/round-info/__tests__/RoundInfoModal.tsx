import {
    fireEvent,
    render,
    screen,
    waitForElementToBeRemoved,
} from '@testing-library/react';

import RoundInfoModal from '../RoundInfoModal';

test('renders the modal', async () => {
    render(
        <RoundInfoModal
            launchTime={Date.now()}
            roundInfo={{name: 'My great level'}}
        />
    );

    expect(screen.getByText('My great level')).not.toBeNull();

    fireEvent.click(screen.getByLabelText('Close'));
    await waitForElementToBeRemoved(() => screen.queryByText('My great level'));
});
