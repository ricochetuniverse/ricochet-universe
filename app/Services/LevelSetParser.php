<?php

namespace App\Services;

use App\LevelRound;
use App\LevelSet;

class LevelSetParser
{
    /**
     * @var LevelSet
     */
    private $levelSet;

    /**
     * @var LevelRound
     */
    private $levelRound;

    /**
     * @param string $levelSetData
     * @throws \Exception
     */
    public function parse(string $levelSetData)
    {
        $newLine = "\r\n";
        $line = strtok($levelSetData, $newLine);

        // Check first line
        if ($line !== 'CRoundSetUserMade') {
            throw new \Exception('Level sets should be CRoundSetUserMade as the first line');
        }

        $this->levelSet = new LevelSet;
        $levelRound = null;

        $key = '';
        $nested = ['CRoundSetUserMade'];
        while ($line !== false) {
            $line = strtok($newLine);

            if ($line === false) {
                break;
            }

            $line = ltrim($line);

            if ($line === '{') {
                array_push($nested, $key);

                if ($key === 'Round') {
                    $this->levelRound = new LevelRound;
                }
            } elseif ($line === '}') {
                $popped = array_pop($nested);

                if ($popped === 'Round') {
                    var_dump($this->levelRound);
                }
            } else {
                $temp = explode('=', $line, 2);
                $key = $temp[0];
                $value = $temp[1] ?? '';

                $this->setPropertyForNested(end($nested), $key, $value);
            }
        }

        // Finished...
        var_dump($this->levelSet);
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
                        $this->levelSet->author = $key;
                        break;

                    case 'Description':
                        $this->levelSet->description = $value;
                        break;

                    // case 'Round To Get Image From':
                    default:
                        break;
                }
                break;

            case 'Round':
                switch ($key) {
                    case 'Display Name':
                        $this->levelRound->name = $value;
                        break;

                    case 'Author':
                        $this->levelRound->author = $value;
                        break;

                    case 'Note 1':
                        $this->levelRound->note1 = $value;
                        break;

                    case 'Note 2':
                        $this->levelRound->note2 = $value;
                        break;

                    case 'Note 3':
                        $this->levelRound->note3 = $value;
                        break;

                    case 'Note 4':
                        $this->levelRound->note4 = $value;
                        break;

                    case 'Note 5':
                        $this->levelRound->note5 = $value;
                        break;

                    case 'Source':
                        $this->levelRound->source = $value;
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
