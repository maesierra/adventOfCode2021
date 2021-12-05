<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day4\Board;
use SplFileObject;

class Day4 {


    /**
     * Parses all the boards in the file
     * Passport fields are the following:
     * @param SplFileObject $inputFile file handler already open
     * @return Board[] array containing all the boards in the file
     */
    private function readBoards($inputFile) {
        $res = [];
        $board = null;
        $numbers = [];
        while (!$inputFile->eof()) {
            $line = trim($inputFile->fgets());
            if (!$line) {
                if ($numbers) {
                    $res[] = new Board($numbers);
                }
                $numbers = [];
                continue;
            } else {
                $numbers[] = array_values(array_filter(explode(" ", $line), function($v) {
                    return $v !== "";
                }));
            }

        }
        if ($numbers) {
            $res[] = new Board($numbers);
        }
        return $res;
    }

    /**
     * Checks if the game has ended and any board is in winning state
     * @param $boards
     */
    private function gameEnd($boards) {

    }

    /**
     * https://adventofcode.com/2021/day/4
     *
     * @param $inputFile string file containing a number per line
     * @return int the sum of all unmarked numbers on the winning board multiplied  by the number that was just called when the board won
     * @throws \Exception
     */
    public function question1($inputFile) {
        $handle = new SplFileObject($inputFile);
        $numbers = explode(",", $handle->fgets());
        $boards = $this->readBoards($handle);
        while ($numbers) {
            $number = array_shift($numbers);
            foreach ($boards as $board) {
                $board->playNumber($number);
                if ($board->completed) {
                    return array_sum($board->unmarked()) * $number;
                }
            }
        }
        return 0;
    }

    /**
     * https://adventofcode.com/2021/day/4
     *
     * @param $inputFile string file containing a number per line
     * @return int the sum of all unmarked numbers on the winning board multiplied  by the number that was just called when the the last board is completed
     * @throws \Exception
     */
    public function question2($inputFile) {
        $handle = new SplFileObject($inputFile);
        $numbers = explode(",", $handle->fgets());
        $boards = $this->readBoards($handle);
        while ($numbers) {
            $number = array_shift($numbers);
            foreach ($boards as $pos => $board) {
                $board->playNumber($number);
                if ($board->completed) {
                    if (count($boards) == 1) {
                        return array_sum($board->unmarked()) * $number;
                    }
                    echo "board $pos is completed\n";
                    unset($boards[$pos]);
                }
            }
        }
        return 0;
    }


}