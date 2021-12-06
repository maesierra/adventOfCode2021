<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day6\Lanternfish;

class Day6 {

    /**
     * Reads all ihe starting counters for the current lanternfish population
     * @param $inputFile
     * @return int[] counter => number of lanternfish
     */
    private function readInput($inputFile) {
        return array_values(array_reduce(explode(",", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $value = (int) trim($line);
                $result[$value] = $result[$value] + 1;
                return $result;
            }, [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0, 6 => 0, 7 => 0, 8 => 0]));
    }

    /**
     * https://adventofcode.com/2021/day/6
     * @param $inputFile
     * @param bool $verbose
     * @return int number of lanternfish after the given number of days
     */
    public function question1($inputFile, $verbose = false) {
        $population = $this->readInput($inputFile);
        $population = $this->simulatePopulation($population, 80, $verbose);
        return array_sum(array_values($population));
    }


    /**
     * https://adventofcode.com/2021/day/6
     * @param $inputFile
     * @param bool $verbose
     * @return int number of lanternfish after the given number of days
     */
    public function question2($inputFile, $verbose = false) {
        $population = $this->readInput($inputFile);
        $population = $this->simulatePopulation($population, 256, $verbose);
        return array_sum(array_values($population));
    }

    /**
     * @param array $population
     * @param int $days
     * @param bool $verbose
     * @return array
     */
    private function simulatePopulation(array $population, int $days, bool $verbose): array {
        echo "Day 0: " . array_sum(array_values($population)) . "\n";
        for ($day = 1; $day <= $days; $day++) {
            $completedCycleToday = array_shift($population);
            $population[6] += $completedCycleToday;
            $population[8] = $completedCycleToday;
            if ($verbose) {
                echo "Day $day: " . implode(",", $population) . "\n";
            } else {
                echo "Day $day: " . array_sum(array_values($population)) . "\n";
            }
        }
        return $population;
    }
}