import {expect, test} from '@jest/globals';
import {render, screen} from '@testing-library/preact';

import DecompressorApp from '../../../resources/js/decompressor/DecompressorApp';

test('renders the app', async () => {
    render(<DecompressorApp dotnetLoaderUrl="" />);

    expect(await screen.findByText('Decompressor')).not.toBeNull();
});
