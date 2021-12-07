<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




use Closure;

class Day7 {

    /** @var int[] */
    static $cache;
    /**
     * Reads all ihe starting positions for crab submarines
     * @param $inputFile
     * @return int[] positions
     */
    private function readInput($inputFile) {
        return array_values(array_reduce(explode(",", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $value = (int) trim($line);
                $result[] = $value;
                return $result;
            }, []));
    }

    /**
     * @param $startingPositions int[]
     * @return int[]
     */
    private function allPositions(array $startingPositions):array {
        sort($startingPositions);
        return range(reset($startingPositions), end($startingPositions));
    }


    /**
     * https://adventofcode.com/2021/day/7
     * @param $inputFile
     * @return int minimum fuel required
     */
    public function question1($inputFile): int {
        return $this->calculateFuel($inputFile, function($v, $position) {
            return abs($v - $position);
        });
    }

    /**
     * @param string $inputFile
     * @return int
     */
    public function question2(string $inputFile) {
        self::$cache = [];
        return $this->calculateFuel($inputFile, function($v, $position)  {
            $diff = abs($v - $position);
            if ($diff <= 1) {
                return $diff;
            }
            $fuel = self::$cache[$diff] ?? 0;
            if ($fuel > 0) {
                return $fuel;
            }
            for ($i = $diff; $i >= 1; $i--) {
                $fuel += $i;
            }
            self::$cache[$diff] = $fuel;
            return $fuel;
        });
    }

    /**
     * @param $inputFile
     * @param $fuelCalculation Closure taking $v, $postion to return the fuel calculation for the given value and position
     * @return int
     */
    private function calculateFuel($inputFile, $fuelCalculation): int
    {
        $startingPositions = $this->readInput($inputFile);
        $min = PHP_INT_MAX;
        $allPositions = $this->allPositions($startingPositions);
        $total = end($allPositions);
        foreach ($allPositions as $position) {
            $fuel = 0;
            foreach ($startingPositions as $p => $v) {
                $fuel += $fuelCalculation($v, $position);
                if ($fuel >= $min) {
                    break;
                }
            }
            if ($fuel < $min) {
                $min = $fuel;
            }
            echo "Pos $position ($total) => $fuel ($min)\n";
        }
        return $min;
    }
}