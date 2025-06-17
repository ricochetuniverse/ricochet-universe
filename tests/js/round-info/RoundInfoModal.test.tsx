import {
    fireEvent,
    render,
    screen,
    waitForElementToBeRemoved,
} from '@testing-library/preact';

import RoundInfoModal from '../../../resources/js/round-info/RoundInfoModal';

test('renders the modal', async () => {
    render(<RoundInfoModal roundInfo={{name: 'My great level'}} />);

    expect(screen.getByText('My great level')).not.toBeNull();

    fireEvent.click(screen.getByLabelText('Close'));
    await waitForElementToBeRemoved(() => screen.queryByText('My great level'));
});
