<?php

namespace App\Services;

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

    private $currentLevelRound = [];

    private $currentLevelRoundPicture = '';

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
        $newLine = "\r\n";
        $nested = [];
        $line = strtok($levelSetData, $newLine);
        while ($line !== false) {
            $line = ltrim($line, "\t");

            if ($line === '{') {
                array_push($nested, $key);

                if ($key === 'Round') {
                    $this->currentLevelRound = [];
                } elseif ($key === 'Compressed Thumbnail') {
                    $this->currentLevelRoundPicture = '';
                }
            } elseif ($line === '}') {
                $popped = array_pop($nested);

                if ($popped === 'Round') {
                    array_push($rounds, $this->currentLevelRound);
                } elseif ($popped === 'Compressed Thumbnail') {
                    $this->currentLevelRound['picture'] = $this->decodeAsciiPicture($this->currentLevelRoundPicture);
                }
            } else {
                if (end($nested) !== 'Compressed Thumbnail') {
                    $temp = explode('=', $line, 2);
                    $key = $temp[0];
                    $value = $temp[1] ?? '';

                    $this->setPropertyForNested(end($nested), $key, $value);
                } else {
                    // Collect all the strings to concat them in the end
                    $key = '';
                    $this->currentLevelRoundPicture .= $line;
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
            ],
            'rounds'   => $rounds,
        ];
    }

    /**
     * @param string $nested
     * @param string $key
     * @param string $value
     */
    private function setPropertyForNested(string $nested, string $key, string $value)
    {
        switch ($nested) {
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
