<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use const true;

class Day3 {


    /**
     * @param string $inputFile
     * @return array|\bool[][]
     */
    protected function readBinaryInput(string $inputFile)
    {
        //Convert the file into a boolean matrix
        return array_map(function ($line) {
            return array_map(function ($char) {
                return $char == '1';
            }, str_split($line));
        }, explode("\n", file_get_contents($inputFile)));
    }


    /**
     * @param array $binaryInput
     * @return string
     */
    private function calculateGammaRate(array $binaryInput): string
    {
        $binary = array_reduce(array_keys($binaryInput[0]), function ($rate, $column) use ($binaryInput) {
            return $rate.$this->commonBit($binaryInput, $column);
        }, "");
        return bindec($binary);
    }

    private function commonBit(array $binaryInput, $pos, $mostCommon = true, $default = "1"):string {
        $row = array_column($binaryInput, $pos);
        $sum = array_sum($row);
        $half = count($row) / 2;
        if ($sum == $half) {
            return $default;
        } else if ($sum > $half) {
            return $mostCommon ? "1" : "0";
        } else {
            return $mostCommon ? "0": "1";
        }
    }

    /**
     * https://adventofcode.com/2021/day/3
     *
     * @param $inputFile string file containing a number per line
     * @return int
     * @throws \Exception
     */
    public function question1($inputFile) {
        $binaryInput = $this->readBinaryInput($inputFile);
        $nBits = count($binaryInput[0]);
        $gamma = $this->calculateGammaRate($binaryInput);
        $epsilon = pow(2, $nBits) - $gamma - 1; //Inverting n bits
        return $gamma * $epsilon;
    }

    public function question2(string $inputFile) {
        $binaryInput = $this->readBinaryInput($inputFile);
        $oxygenGeneratorRating = $this->extractNumberFromInput($binaryInput, true, "1");
        $co2ScrubberRating = $this->extractNumberFromInput($binaryInput, false, "0");
        return $oxygenGeneratorRating * $co2ScrubberRating;
    }

    /**
     * @param array $binaryInput
     * @param $mostCommon
     * @param $default
     * @return int
     */
    private function extractNumberFromInput(array $binaryInput, $mostCommon, $default): int {
        $pos = 0;
        while (count($binaryInput) > 1) {
            $mostCommonBit = $this->commonBit($binaryInput, $pos, $mostCommon, $default);
            $binaryInput = array_filter($binaryInput, function ($n) use ($pos, $mostCommonBit) {
                return ($n[$pos] ? "1" : "0") == $mostCommonBit;
            });
            $pos++;
        }
        return bindec(implode("", array_map(function ($b) {
            return $b ? "1" : "0";
        }, reset($binaryInput))));
    }
}