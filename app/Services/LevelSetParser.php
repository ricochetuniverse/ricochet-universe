<?php

namespace App\Services;

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
    /**
     * @var string
     */
    private $levelSetAuthor = '';

    /**
     * @var string
     */
    private $levelSetDescription = '';

    /**
     * @var int
     */
    private $levelSetRoundToGetImageFrom = 1;

    /**
     * @var array Text strings of which mods are used
     */
    private $levelSetModsUsed = [];

    private $currentLevelRound = [];
    private $currentLevelRoundPicture = '';
    private $currentRecallButtonPressedCondition = ['left' => true, 'right' => true];

    private $fileGroups = [
        'environments' => ['Background Type'],
        'sequences' => [
            'Frame',
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
    private $fileGroupsKeyed = [];
    private $modFiles = [
        'Neon Environment' => [
            'environments' => ['Environments/Neon/'],
            'sequences' => [
                'Addon/Custom/Bricks/Neon/',
                'Addon/Custom/Environments/Neon/',
            ],
            'sounds' => ['Addon/Custom/Special Bricks/Switch Neon'],
            'bricks' => ['Neon/'],
            'music' => ['Neon/'],
            'powerups' => ['Multiply 8 Inline'],
        ],
    ];

    public function __construct()
    {
        foreach ($this->fileGroups as $group => $properties) {
            foreach ($properties as $property) {
                $this->fileGroupsKeyed[$property] = $group;
            }
        }
    }

    /**
     * @param string $levelSetData
     * @return array
     * @throws \Exception
     */
    public function parse(string $levelSetData)
    {
        // Check first line
        if (substr($levelSetData, 0, strlen('CRoundSetUserMade')) !== 'CRoundSetUserMade') {
            throw new \Exception('Level sets should be CRoundSetUserMade as the first line');
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
                    array_push($rounds, $this->currentLevelRound);
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
                'author'              => $this->levelSetAuthor,
                'description'         => $this->levelSetDescription,
                'roundToGetImageFrom' => $this->levelSetRoundToGetImageFrom,
                'modsUsed'            => $this->levelSetModsUsed,
            ],
            'rounds'   => $rounds,
        ];
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function checkProperty(string $key, string $value)
    {
        if (isset($this->fileGroupsKeyed[$key])) {
            foreach ($this->modFiles as $modName => $modFileGroups) {
                if (in_array($modName, $this->levelSetModsUsed)) {
                    continue;
                }

                $type = $this->fileGroupsKeyed[$key];

                if (isset($modFileGroups[$type])) {
                    if (Str::startsWith($value, $modFileGroups[$type])) {
                        $this->levelSetModsUsed[] = $modName;
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

    /**
     * @param string $ascii
     * @return string
     */
    private function decodeAsciiPicture(string $ascii)
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
     * @return null|string|string[]
     */
    private function fixEncoding(string $value)
    {
        return mb_convert_encoding($value, 'UTF-8', 'Windows-1252');
    }
}
