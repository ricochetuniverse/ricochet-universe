import {create} from 'zustand';

import type {Appearance} from './results/DecompressorResultsImage';

type SettingsStore = {
    image: {
        currentIndex: number;
        setCurrentIndex: (currentIndex: number) => void;

        showAll: boolean;
        setShowAll: (showAll: boolean) => void;

        appearance: Appearance;
        setAppearance: (appearance: Appearance) => void;
    };

    text: {
        show: boolean;
        setShow: (showAll: boolean) => void;
    };
};

export const useSettingsStore = create<SettingsStore>((set) => ({
    image: {
        currentIndex: 0,
        setCurrentIndex: (currentIndex: number) => {
            set((state) => {
                return {
                    image: {
                        ...state.image,
                        currentIndex,
                    },
                };
            });
        },

        showAll: false,
        setShowAll: (showAll: boolean) => {
            set((state) => {
                return {
                    image: {
                        ...state.image,
                        showAll,
                    },
                };
            });
        },

        appearance: 'CHECKERBOARD',
        setAppearance: (appearance: Appearance) => {
            set((state) => {
                return {
                    image: {
                        ...state.image,
                        appearance,
                    },
                };
            });
        },
    },

    text: {
        show: false,
        setShow: (show: boolean) => {
            set((state) => {
                return {
                    text: {
                        ...state.text,
                        show,
                    },
                };
            });
        },
    },
}));
