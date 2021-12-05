<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day5\Line;

class Day5 {


    /**
     * Reads all lines in the input file
     * @param $inputFile
     * @param bool $ignoreDiagonals
     * @return Line[]
     */
    private function readLines($inputFile, bool $ignoreDiagonals = true) {
        return array_reduce(explode("\n", file_get_contents($inputFile)), function(&$result, $text) use($ignoreDiagonals) {
            $text = trim($text);
            if (!$text) {
                return $result;
            }
            if (preg_match('/^(\d+),(\d+) -> (\d+),(\d+)$/', $text, $matches)) {
                $line = new Line($matches[1], $matches[2], $matches[3], $matches[4]);
                if (!$ignoreDiagonals || $line->lineType() != Line::LINE_DIAGONAL) {
                    $result[] = $line;
                }
            }
            return $result;
        }, []);
    }

    private function drawMap($map) {
        return implode("\n", array_map(function($row) {
            return implode("", $row);
        }, $map));
    }

    /**
     * @param array $lines
     * @param bool $verbose
     * @return mixed
     */
    private function applyAndCount(array $lines, bool $verbose)
    {
        $n = 0;
        $total = count($lines);
        $map = array_reduce($lines, function ($res, $line) use (&$n, $total, $verbose) {
            /** @var $line Line */
            $newMap = $line->apply($res);
            $n++;
            echo "applying $line ($n/$total)\n";
            if ($verbose) {
                echo $this->drawMap($newMap) . "\n";
            }
            return $newMap;
        }, []);
        return array_reduce($map, function ($count, $row) {
            return $count + count(array_filter($row, function ($v) {
                    return $v >= 2;
                }));
        }, 0);
    }


    /**
     * https://adventofcode.com/2021/day/5
     * @param $inputFile
     * @param bool $verbose
     * @return int
     */
    public function question1($inputFile, $verbose = false) {
        $lines = $this->readLines($inputFile);
        return $this->applyAndCount($lines, $verbose);
    }

    /**
     * https://adventofcode.com/2021/day/5
     * @param $inputFile
     * @param bool $verbose
     * @return int
     */
    public function question2($inputFile, $verbose = false) {
        $lines = $this->readLines($inputFile, false);
        return $this->applyAndCount($lines, $verbose);
    }

}