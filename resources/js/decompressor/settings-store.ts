import {create} from 'zustand';

import type {Appearance} from './results/DecompressorResultsImage';

type SettingsStore = {
    image: {
        currentIndex: number;
        showAll: boolean;
        appearance: Appearance;

        setCurrentIndex: (currentIndex: number) => void;
        setShowAll: (showAll: boolean) => void;
        setAppearance: (appearance: Appearance) => void;
    };
};

export const useSettingsStore = create<SettingsStore>((set) => ({
    image: {
        currentIndex: 0,
        showAll: false,
        appearance: 'CHECKERBOARD',

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
}));
