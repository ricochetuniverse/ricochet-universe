<?php

namespace App\Services;

use App\LevelRound;

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
     * @var LevelRound
     */
    private $currentLevelRound;

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
            $line = ltrim($line);

            if ($line === '{') {
                array_push($nested, $key);

                if ($key === 'Round') {
                    $this->currentLevelRound = new LevelRound;
                    $this->currentLevelRound->image_file_name = ''; // FIXME
                }
            } elseif ($line === '}') {
                $popped = array_pop($nested);

                if ($popped === 'Round') {
                    array_push($rounds, $this->currentLevelRound);
                }
            } else {
                $temp = explode('=', $line, 2);
                $key = $temp[0];
                $value = $temp[1] ?? '';

                $this->setPropertyForNested(end($nested), $key, $value);
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
                        $this->levelSetAuthor = $value;
                        break;

                    case 'Description':
                        $this->levelSetDescription = $value;
                        break;

                    case 'Round To Get Image From':
                        $this->levelSetRoundToGetImageFrom = (int)$value;
                        break;

                    default:
                        break;
                }
                break;

            case 'Round':
                switch ($key) {
                    case 'Display Name':
                        $this->currentLevelRound->name = $value;
                        break;

                    case 'Author':
                        $this->currentLevelRound->author = $value;
                        break;

                    case 'Note 1':
                        $this->currentLevelRound->note1 = $value;
                        break;

                    case 'Note 2':
                        $this->currentLevelRound->note2 = $value;
                        break;

                    case 'Note 3':
                        $this->currentLevelRound->note3 = $value;
                        break;

                    case 'Note 4':
                        $this->currentLevelRound->note4 = $value;
                        break;

                    case 'Note 5':
                        $this->currentLevelRound->note5 = $value;
                        break;

                    case 'Source':
                        $this->currentLevelRound->source = $value;
                        break;

                    case 'Compressed Thumbnail':
                        break;

                    default:
                        break;
                }
                break;

            default:
                break;
        }
    }
}
