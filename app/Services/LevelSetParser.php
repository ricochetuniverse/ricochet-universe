<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Naive parser for Ricochet Infinity levels
 *
 * I'm open for code suggestions =p
 *
 * @package App\Services
 */
class LevelSetParser
{
    private string $levelSetAuthor = '';

    private string $levelSetDescription = '';

    private int $levelSetRoundToGetImageFrom = 1;

    private array $levelSetModsUsed = [];

    private array $currentLevelRound = [];
    private string $currentLevelRoundPicture = '';
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
     * @param string $levelSetData
     * @return array
     * @throws \Exception
     */
    public function parse(string $levelSetData): array
    {
        if (!Str::startsWith($levelSetData, 'CRoundSetUserMade')) {
            throw new \Exception('Level sets should start with CRoundSetUserMade as the first line');
        }

        $rounds = [];

        $key = '';
        $value = '';
        $newLine = "\r\n";
        $nested = [];
        $line = strtok($levelSetData, $newLine);
        while ($line !== false) {
            $line = ltrim($line, "\t");

            if ($line === '{') {
                $nested[] = ['key' => $key, 'value' => $value];

                if ($key === 'Round') {
                    $this->currentLevelRound = [];
                } elseif ($key === 'Compressed Thumbnail') {
                    $this->currentLevelRoundPicture = '';
                } elseif ($key === 'Condition' && $value === 'CExpressionRecallButtonPressed') {
                    $this->currentRecallButtonPressedCondition = ['left' => true, 'right' => true];
                }
            } elseif ($line === '}') {
                $popped = array_pop($nested);

                if ($popped['key'] === 'Round') {
                    $rounds[] = $this->currentLevelRound;
                } elseif ($popped['key'] === 'Compressed Thumbnail') {
                    $last = end($nested);

                    if ($last && $last['key'] === 'Round') {
                        $this->currentLevelRound['picture'] = $this->decodeAsciiPicture($this->currentLevelRoundPicture);
                    }
                } elseif ($popped['key'] === 'Condition' && $popped['value'] === 'CExpressionRecallButtonPressed') {
                    if (!$this->currentRecallButtonPressedCondition['left'] || !$this->currentRecallButtonPressedCondition['right']) {
                        $this->currentLevelRound['iphone_specific'] = true;
                    }

                    // reset state
                    $this->currentRecallButtonPressedCondition = ['left' => true, 'right' => true];
                }
            } else {
                $last = end($nested);
                if ($last && $last['key'] === 'Compressed Thumbnail') {
                    // Collect all the strings to concat them in the end
                    $key = '';
                    $this->currentLevelRoundPicture .= $line;
                } else {
                    $temp = explode('=', $line, 2);
                    $key = $temp[0];
                    $value = $temp[1] ?? '';

                    $this->checkProperty($key, $value);
                    if ($last !== false) {
                        $this->setPropertyForNested($last, $key, $value);
                    }
                }
            }

            $line = strtok($newLine);
        }

        strtok('', '');

        // Finished...
        return [
            'levelSet' => [
                'author' => $this->levelSetAuthor,
                'description' => $this->levelSetDescription,
                'roundToGetImageFrom' => $this->levelSetRoundToGetImageFrom,
                'modsUsed' => $this->levelSetModsUsed,
            ],
            'rounds' => $rounds,
        ];
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function checkProperty(string $key, string $value)
    {
        foreach (static::$modInfo as $modName => $modFileTypeGroups) {
            if (in_array($modName, $this->levelSetModsUsed, true)) {
                continue;
            }

            if (isset(static::$propertyReferencesReversed[$key])) {
                $type = static::$propertyReferencesReversed[$key];

                if (isset($modFileTypeGroups[$type])) {
                    foreach ($modFileTypeGroups[$type] as $file) {
                        if ($value === $file) {
                            $this->levelSetModsUsed[] = $modName;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param array $nested
     * @param string $key
     * @param string $value
     */
    private function setPropertyForNested(array $nested, string $key, string $value)
    {
        switch ($nested['key']) {
            case 'CRoundSetUserMade':
                switch ($key) {
                    case 'Author':
                        $this->levelSetAuthor = $this->fixEncoding($value);
                        break;

                    case 'Description':
                        $this->levelSetDescription = $this->fixEncoding($value);
                        break;

                    case 'Round To Get Image From':
                        // First round starts from 0
                        $this->levelSetRoundToGetImageFrom = ((int)$value) + 1;
                        break;

                    default:
                        break;
                }
                break;

            case 'Round':
                switch ($key) {
                    case 'Display Name':
                        $this->currentLevelRound['name'] = $this->fixEncoding($value);
                        break;

                    case 'Author':
                        $this->currentLevelRound['author'] = $this->fixEncoding($value);
                        break;

                    case 'Note 1':
                        $this->currentLevelRound['note1'] = $this->fixEncoding($value);
                        break;

                    case 'Note 2':
                        $this->currentLevelRound['note2'] = $this->fixEncoding($value);
                        break;

                    case 'Note 3':
                        $this->currentLevelRound['note3'] = $this->fixEncoding($value);
                        break;

                    case 'Note 4':
                        $this->currentLevelRound['note4'] = $this->fixEncoding($value);
                        break;

                    case 'Note 5':
                        $this->currentLevelRound['note5'] = $this->fixEncoding($value);
                        break;

                    case 'Source':
                        $this->currentLevelRound['source'] = $this->fixEncoding($value);
                        break;

                    default:
                        break;
                }
                break;

            case 'Condition':
                if ($nested['value'] === 'CExpressionRecallButtonPressed') {
                    switch ($key) {
                        case 'Check For Left Recall':
                            $this->currentRecallButtonPressedCondition['left'] = (bool)$value;
                            break;

                        case 'Check For Right Recall':
                            $this->currentRecallButtonPressedCondition['right'] = (bool)$value;
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

    /**
     * @param string $ascii
     * @return string
     */
    private function decodeAsciiPicture(string $ascii): string
    {
        $decoded = $ascii;
        // $decoded = str_replace(["\r\n", "\n"], '', $decoded);
        $decoded = str_replace(chr(33) . chr(35), chr(255), $decoded);
        $decoded = str_replace(chr(33) . chr(36), chr(123), $decoded);
        $decoded = str_replace(chr(33) . chr(37), chr(125), $decoded);
        for ($i = 38; $i <= 70; $i += 1) {
            // min/max:
            // $decoded = str_replace(chr(33).chr(38), chr(0), $decoded);
            // $decoded = str_replace(chr(33).chr(70), chr(32), $decoded);

            $decoded = str_replace(chr(33) . chr($i), chr($i - 38), $decoded);
        }
        $decoded = str_replace(chr(33) . chr(34), chr(33), $decoded);

        return $decoded;
    }

    /**
     * @param string $value
     * @return string
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
