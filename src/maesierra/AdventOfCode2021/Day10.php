<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day10\Node;

class Day10 {

    const CHARS = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    const SCORE_MAP = [
        ')' => 3,
        ']' => 57,
        '}' => 1197,
        '>' => 25137,
        '(' => 1,
        '[' => 2,
        '{' => 3,
        '<' => 4,
    ];

    /**
     * https://adventofcode.com/2021/day/10
     * @param $inputFile
     * @return int syntax error score for all the errors found in the file
     */
    public function question1($inputFile): int {
        return array_reduce($this->readLines($inputFile), function($score, $line) {
            list($invalidChar, $unmatched) = $this->parseLine($line);
            if ($invalidChar) {
                $score += self::SCORE_MAP[$invalidChar];
            }
            return $score;
        }, 0);
    }

    /**
     * https://adventofcode.com/2021/day/10
     * @param $inputFile
     * @return int completion score for all the incomplete lines found in the file
     */
    public function question2($inputFile): int {
        $scores = array_reduce($this->readLines($inputFile), function (&$res, $line) {
            list($invalidChar, $unmatched) = $this->parseLine($line);
            if (!$invalidChar) {
                $res[] = $this->calculateAutocompleteScore($unmatched);
            }
            return $res;
        }, []);
        sort($scores);
        $middlePoint = (int)(count($scores) / 2) ;
        return $scores[$middlePoint];


    }


    /**
     * @param $inputFile string
     * @return int[][]
     */
    private function readLines(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $result[] = str_split($line);
                return $result;
            }, []);
    }

    private function parseLine($line) {
        $stack = [];
        foreach ($line as $pos => $char) {
            if (isset(self::CHARS[$char])) {
                $stack[] = $char;
            } else {
                if (!$stack) {
                    return [$char, $stack];
                }
                $expected = self::CHARS[array_pop($stack)];
                if ($expected != $char) {
                    return [$char, $stack];
                }

            }
        }
        return [false, $stack];
    }

    private function calculateAutocompleteScore($toComplete) {
        $reversed = array_reverse($toComplete);
        return array_reduce($reversed, function($score, $char) {
                return $score * 5 + self::SCORE_MAP[$char];
        }, 0);
    }


}