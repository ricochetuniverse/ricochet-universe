export type ModRequirement =
    | {
          result: true;
          mods: string[];
      }
    | {
          result: false;
      };

const POWERUP_USAGES = [
    'Forced Power-Up',
    'Automatic',
    'Disallowed',
    'Powerup', // CLotteryList
];

const NEON_FILES =
    /(Addon\/Custom\/Bricks\/Neon\/|Custom\/Neon\/Bricks\/|Environments\/Neon\/|Addon\/Custom\/Special Bricks\/Switch Neon)/;
const NEON_PROPERTIES =
    /(Style Sheet=Neon\/|Style Sheet=Neon Night\/|Music To Play=Neon\/)/;
const NEON_POWERUPS = ['Multiply 8 Inline'];

const HEAVYMETAL_FILES =
    /(Bricks\/Heavy Metal\/|Effects\/Shock Charge Zap|Environments\/Heavy Metal\/|Special Bricks\/Heavy Metal\/)/;
const HEAVYMETAL_PROPERTIES =
    /(Style Sheet=Heavy Metal\/|Music To Play=Music\/Heavy Metal\/)/;

const HEX_FILES = /(Bricks\/HEX\/|Environments\/HEX\/|Special Bricks\/HEX\/)/;
const HEX_PROPERTIES = /(Style Sheet=HEX\/|Music To Play=Music\/HEX\/)/;

function hasPowerup(data: string, powerups: string[]) {
    for (let i = 0; i < powerups.length; i += 1) {
        for (let j = 0; j < POWERUP_USAGES.length; j += 1) {
            if (data.includes(POWERUP_USAGES[j] + '=' + powerups[i])) {
                return true;
            }
        }
    }

    return false;
}

export default function (data: string): ModRequirement {
    const mods = [];

    if (data.match(NEON_FILES) || data.match(NEON_PROPERTIES)) {
        mods.push('Neon Environment');
    } else if (hasPowerup(data, NEON_POWERUPS)) {
        mods.push('Neon Environment');
    }

    if (data.match(HEAVYMETAL_FILES) || data.match(HEAVYMETAL_PROPERTIES)) {
        mods.push('Heavy Metal Environment');
    }

    if (data.match(HEX_FILES) || data.match(HEX_PROPERTIES)) {
        mods.push('HEX');
    }

    if (mods.length) {
        return {
            result: true,
            mods,
        };
    }

    return {result: false};
}
