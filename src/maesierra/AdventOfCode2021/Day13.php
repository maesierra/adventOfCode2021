<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;






use maesierra\AdventOfCode2021\Day13\FoldInstruction;
use maesierra\AdventOfCode2021\Day8\Reading;

class Day13 {

    /**
     * @param $inputFile string
     * @return bool[][]
     */
    private function parseGrid(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $matches = [];
                if (preg_match('/^(\d+),(\d+)$/', $line, $matches)) {
                    list($m, $x, $y) = $matches;
                    if (!isset($result[$y])) {
                        $nCols = count($result[0] ?? []);
                        for ($i = count($result); $i <= $y; $i++) {
                            $result[$i] = array_fill(0, $nCols, false);
                        }
                    }
                    if (!isset($result[$y][$x])) {
                        $currentCols = count($result[0]);
                        $nCols = $x - $currentCols +1 ;
                        foreach ($result as $r => $row) {
                            $result[$r] = array_merge($result[$r] , array_fill($currentCols, $nCols, false));
                        }
                    }
                    $result[$y][$x] = true;
                }
                return $result;
            }, []);
    }

    /**
     * @param $inputFile string
     * @return FoldInstruction[]
     */
    private function parseInstructions(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $matches = [];
                if (preg_match('/^fold along ([xy])=(\d+)$/', $line, $matches)) {
                    list($m, $axis, $value) = $matches;
                    $result[] = new FoldInstruction($axis, (int)$value);
                }
                return $result;
            }, []);
    }

    private static function print($grid):string {
        return implode("\n", array_map(function($r) {
            return array_reduce($r, function($str, $point) {
                return $str.($point ? '#' : '.');
            }, "");
        }, $grid));
    }


    /**
     * https://adventofcode.com/2021/day/13
     * @param $inputFile
     * @return int number of dots that are visible after completing just the first fold instruction on your transparent paper
     */
    public function question1($inputFile): int {
        $grid = $this->parseGrid($inputFile);
        $instructions = $this->parseInstructions($inputFile);
        $grid = $instructions[0]->fold($grid);
        return array_sum(array_map(function ($row) {
            return array_sum($row);
        }, $grid));
    }

    /**
     * @param string $inputFile
     * @return int
     */
    public function question2(string $inputFile) {
        $grid = $this->parseGrid($inputFile);
        $instructions = $this->parseInstructions($inputFile);
        $folded = array_reduce($instructions, function ($res, $i) {
            /** @var $i FoldInstruction */
            echo "Folding {$i->axis} {$i->line}\n";
            return $i->fold($res);
        }, $grid);
        return self::print($folded);
    }

}