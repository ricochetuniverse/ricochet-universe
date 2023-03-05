<?php

namespace App\Services\LevelSetParser;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Parser for Ricochet Lost Worlds/Infinity levels
 */
class Parser
{
    private array $currentRecallButtonPressedCondition = ['left' => true, 'right' => true];

    private static array $propertyReferences = [
        'environments' => ['Background Type'],
        'frames' => ['Frame'],
        'sequences' => [
            'Image',
            'Poisoned Effect Overlay',
        ],
        'sounds' => [
            'Sound',
            'Change Sound',
            'Damaged Sound',
            'Trigger Sound',
            'Shot Sound',
        ],
        'bricks' => [
            'Style Sheet',
            'Create Brick Style',
            'Change To Brick Style',
            'Change From Brick Style',
            'Brick Style', // CExpressionNumberOfBricksOfStyle
        ],
        'music' => ['Music To Play'],
        'powerups' => [
            'Forced Power-Up',
            'Automatic',
            'Disallowed',
            'Powerup', // CLotteryList
        ],
    ];

    private static array $propertyReferencesReversed = [];

    private static array $modInfo = [];

    public function __construct()
    {
        if (empty(static::$propertyReferencesReversed)) {
            foreach (static::$propertyReferences as $group => $properties) {
                foreach ($properties as $property) {
                    static::$propertyReferencesReversed[$property] = $group;
                }
            }
        }

        if (empty(static::$modInfo)) {
            $this->preloadModInfo();
        }
    }

    private static function preloadModInfo(): void
    {
        $disk = Storage::disk('mod-info');
        foreach ($disk->files('.') as $modFile) {
            $info = json_decode($disk->read($modFile), true);

            $types = [];
            foreach ($info['files'] as $file) {
                if (Str::endsWith($file, '.Background')) {
                    $types['environments'][] = Str::after(
                        Str::beforeLast($file, '.Background'),
                        'Resources/'
                    );
                } elseif (Str::endsWith($file, '.Sequence')) {
                    $types['sequences'][] = Str::after(
                        Str::beforeLast($file, '.Sequence'),
                        'Cache/Resources/'
                    );
                } elseif (Str::endsWith($file, '.Frame')) {
                    $types['frames'][] = Str::after(
                        Str::beforeLast($file, '.Frame'),
                        'Cache/Resources/'
                    );
                } elseif (Str::endsWith($file, '.ogg')) {
                    if (Str::startsWith($file, 'Music/')) {
                        // Music To Play property intentionally includes .ogg
                        $types['music'][] = Str::after($file, 'Music/');
                    } else {
                        $types['sounds'][] = Str::after(
                            Str::beforeLast($file, '.ogg'),
                            'Sounds/'
                        );
                    }
                } elseif (Str::endsWith($file, '.BrickStyleSheet')) {
                    $types['bricks'][] = Str::after(
                        Str::beforeLast($file, '.BrickStyleSheet'),
                        'Resources/Brick Style Sheets/'
                    );
                } elseif (Str::endsWith($file, '.PowerUp')) {
                    $types['powerups'][] = Str::after(
                        Str::beforeLast($file, '.PowerUp'),
                        'Resources/Power Ups/'
                    );
                } else {
                    // Unknown??
                }
            }

            static::$modInfo[$info['trigger_codename']] = $types;
        }
    }

    /**
     * @throws \Exception
     */
    public function parse(string $rawData): LevelSet
    {
        if (! Str::startsWith($rawData, 'CRoundSetUserMade')) {
            throw new \Exception('Level sets should start with CRoundSetUserMade as the first line');
        }

        $levelSet = new LevelSet();

        $nested = [];
        $previousKey = '';
        $previousValue = '';
        $currentWorkingRound = null;
        $currentWorkingRoundPicture = '';

        $newLine = "\r\n";
        $line = strtok($rawData, $newLine);
        while ($line !== false) {
            $line = ltrim($line, "\t");

            if ($line === '{') {
                $nested[] = ['key' => $previousKey, 'value' => $previousValue];

                if ($previousKey === 'Round') {
                    $currentWorkingRound = new Round();
                } elseif ($previousKey === 'Compressed Thumbnail') {
                    $currentWorkingRoundPicture = '';
                } elseif ($previousKey === 'Condition' && $previousValue === 'CExpressionRecallButtonPressed') {
                    $this->currentRecallButtonPressedCondition = ['left' => true, 'right' => true];
                }
            } elseif ($line === '}') {
                $popped = array_pop($nested);

                if ($popped['key'] === 'Round') {
                    $levelSet->addRound($currentWorkingRound);
                    $currentWorkingRound = null;
                } elseif ($popped['key'] === 'Compressed Thumbnail') {
                    $lastNested = end($nested);

                    if ($lastNested && $lastNested['key'] === 'Round') {
                        $currentWorkingRound->thumbnail = $this->decodeAsciiPicture($currentWorkingRoundPicture);
                        $currentWorkingRoundPicture = '';
                    }
                } elseif ($popped['key'] === 'Condition' && $popped['value'] === 'CExpressionRecallButtonPressed') {
                    if (! $this->currentRecallButtonPressedCondition['left'] || ! $this->currentRecallButtonPressedCondition['right']) {
                        $currentWorkingRound->iphoneSpecific = true;
                    }

                    // reset state
                    $this->currentRecallButtonPressedCondition = ['left' => true, 'right' => true];
                }
            } else {
                $lastNested = end($nested);
                if ($lastNested && $lastNested['key'] === 'Compressed Thumbnail') {
                    // Collect all the strings to concat them in the end
                    $previousKey = '';
                    $currentWorkingRoundPicture .= $line;
                } else {
                    $split = strpos($line, '=');
                    if ($split === false) {
                        $split = -1;
                    }
                    $key = substr($line, 0, $split);
                    $value = substr($line, $split + 1);

                    $this->checkPropertyForModUsage($levelSet, $key, $value);

                    if ($lastNested !== false) {
                        if ($lastNested['value'] === 'CRoundSetUserMade') {
                            $this->setPropertyForLevelSet($levelSet, $key, $value);
                        } elseif ($lastNested['key'] === 'Round') {
                            $this->setPropertyForRound($currentWorkingRound, $key, $value);
                        } else {
                            $this->setPropertyForNested($lastNested, $key, $value);
                        }
                    }

                    $previousKey = $key;
                    $previousValue = $value;
                }
            }

            $line = strtok($newLine);
        }

        strtok('', '');

        return $levelSet;
    }

    private function checkPropertyForModUsage(LevelSet $levelSet, string $key, string $value): void
    {
        foreach (static::$modInfo as $modName => $modFileTypeGroups) {
            if (in_array($modName, $levelSet->modsUsed, true)) {
                continue;
            }

            if (isset(static::$propertyReferencesReversed[$key])) {
                $type = static::$propertyReferencesReversed[$key];

                if (isset($modFileTypeGroups[$type])) {
                    foreach ($modFileTypeGroups[$type] as $file) {
                        if ($value === $file) {
                            $levelSet->modsUsed[] = $modName;
                        }
                    }
                }
            }
        }
    }

    private function setPropertyForLevelSet(LevelSet $levelSet, string $key, string $value): void
    {
        switch ($key) {
            case 'Author':
                $levelSet->author = $this->fixEncoding($value);
                break;

            case 'Description':
                $levelSet->description = $this->fixEncoding($value);
                break;

            case 'Round To Get Image From':
                // First round starts from 0
                $levelSet->roundToGetImageFrom = ((int) $value) + 1;
                break;

            default:
                break;
        }
    }

    private function setPropertyForRound(Round $round, string $key, string $value): void
    {
        switch ($key) {
            case 'Display Name':
                $round->name = $this->fixEncoding($value);
                break;

            case 'Author':
                $round->author = $this->fixEncoding($value);
                break;

            case 'Note 1':
                $round->notes[0] = $this->fixEncoding($value);
                break;

            case 'Note 2':
                $round->notes[1] = $this->fixEncoding($value);
                break;

            case 'Note 3':
                $round->notes[2] = $this->fixEncoding($value);
                break;

            case 'Note 4':
                $round->notes[3] = $this->fixEncoding($value);
                break;

            case 'Note 5':
                $round->notes[4] = $this->fixEncoding($value);
                break;

            case 'Source':
                $round->source = $this->fixEncoding($value);
                break;

            default:
                break;
        }
    }

    private function setPropertyForNested(array $nested, string $key, string $value): void
    {
        switch ($nested['key']) {
            case 'Condition':
                if ($nested['value'] === 'CExpressionRecallButtonPressed') {
                    switch ($key) {
                        case 'Check For Left Recall':
                            $this->currentRecallButtonPressedCondition['left'] = (bool) $value;
                            break;

                        case 'Check For Right Recall':
                            $this->currentRecallButtonPressedCondition['right'] = (bool) $value;
                            break;

                        default:
                            break;
                    }
                }
                break;

            default:
                break;
        }
    }

    private function decodeAsciiPicture(string $ascii): string
    {
        $decoded = $ascii;
        // $decoded = str_replace(["\r\n", "\n"], '', $decoded);
        $decoded = str_replace(chr(33).chr(35), chr(255), $decoded);
        $decoded = str_replace(chr(33).chr(36), chr(123), $decoded);
        $decoded = str_replace(chr(33).chr(37), chr(125), $decoded);
        for ($i = 38; $i <= 70; $i += 1) {
            // min/max:
            // $decoded = str_replace(chr(33).chr(38), chr(0), $decoded);
            // $decoded = str_replace(chr(33).chr(70), chr(32), $decoded);

            $decoded = str_replace(chr(33).chr($i), chr($i - 38), $decoded);
        }
        $decoded = str_replace(chr(33).chr(34), chr(33), $decoded);

        return $decoded;
    }

    /**
     * @throws \Exception
     */
    private function fixEncoding(string $value): string
    {
        $converted = mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
        if ($converted === false) {
            throw new \Exception('Cannot convert string to proper encoding');
        }

        return $converted;
    }
}
