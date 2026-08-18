import {afterEach} from '@rstest/core';
import {cleanup as cleanupTestingLibrary} from '@testing-library/preact';

afterEach(() => {
    cleanupTestingLibrary();
});
