<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




class Day11 {

    /**
     * @param int $r
     * @param int $c
     * @return string
     */
    private static function columnKey(int $r, int $c): string {
        return "$r:$c";
    }


    private static function adjacent(array $state, int $row, int $column) {
        $adjacent = [];
        $top = max(0, $row - 1);
        $bottom = min(count($state) - 1, $row + 1);
        $left = max(0, $column - 1);
        $right = min(count($state[0]) - 1, $column + 1);
        for ($r = $top; $r <= $bottom; $r++) {
            for ($c = $left; $c <= $right; $c ++) {
                if (($r == $row) && ($column == $c)) {
                    continue;
                }
                $adjacent[self::columnKey($r, $c)] = $state[$r][$c];
            }
        }
        return $adjacent;
    }

    /**
     * @param $inputFile string
     * @return int[][]
     */
    private function parseInput(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $result[] = array_map(function($v) {
                    return (int) $v;
                }, str_split($line));
                return $result;
            }, []);
    }


    public function applyRules(array $state, &$nFlashes):array {
        //Increase all energy levels
        $new = array_map(function($row) {
            return array_map(function($v) {
                return $v + 1;
            }, $row);
        }, $state);
        $step = 0;
        $flashes = [];
        do {
            $prev = count($flashes);
            foreach ($new as $r => $row) {
                foreach ($row as $c => $level) {
                    $key = self::columnKey($r, $c);
                    if (isset($flashes[$key])) {
                        continue;  //Already flashed
                    }
                    if ($new[$r][$c] > 9) {
                        //It flashes
                        $new[$r][$c] = 0;
                        $flashes[$key] = true;
                        //Increase adjacent levels
                        foreach (self::adjacent($new, $r, $c) as $pos => $value) {
                            if (!isset($flashes[$pos])) {
                                list($r1, $c1) = explode(":", $pos);
                                $new[$r1][$c1] += 1;
                            }
                        }
                    }
                }
            }
            $step++;
        } while (count($flashes) > $prev); //Repeat until stable
        $nFlashes += count($flashes);
        return $new;
    }

    /**
     * https://adventofcode.com/2021/day/11
     * @param $inputFile
     * @return int total flashes after 100 steps
     */
    public function question1($inputFile): int {
        $state = array_map(function($line) {
            return str_split($line);
        }, explode("\n", file_get_contents($inputFile)));
        echo $this->showState($state) ."\n\n";
        $flashes = 0;
        for ($i = 1; $i <= 100; $i++) {
            echo "----------------------------step $i\n";
            $state = $this->applyRules($state, $flashes);
            echo $this->showState($state) ."\n\n";
        }
        return $flashes;
    }


    /**
     * https://adventofcode.com/2021/day/11
     * @param $inputFile
     * @return int total of steps required to get all flashes
     */
    public function question2($inputFile): int {
        $state = array_map(function($line) {
            return str_split($line);
        }, explode("\n", file_get_contents($inputFile)));
        echo $this->showState($state) ."\n\n";
        $flashes = 0;
        $prevFlashes = 0;
        $i = 0;
        while ($flashes - $prevFlashes != 100){
            $prevFlashes = $flashes;
            $i++;
            echo "----------------------------step $i\n";
            $state = $this->applyRules($state, $flashes);
            echo $this->showState($state) ."\n\n";
        }
        return $i;

    }

    /**
     * @param array $new
     * @return string
     */
    private function showState(array $new): string
    {
        $str = implode("\n", array_map(function ($v) {
            return implode("", $v);
        }, $new));
        return $str;
    }

}