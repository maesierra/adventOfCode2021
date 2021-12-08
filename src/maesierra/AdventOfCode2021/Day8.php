<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




use maesierra\AdventOfCode2021\Day8\Acc;
use maesierra\AdventOfCode2021\Day8\Instruction;
use maesierra\AdventOfCode2021\Day8\Jmp;
use maesierra\AdventOfCode2021\Day8\Nop;
use maesierra\AdventOfCode2021\Day8\Reading;
use maesierra\AdventOfCode2021\Day8\Runtime;

class Day8 {

    const DIGITS = [
        0 => 'abcefg',
        1 => 'cf',
        2 => 'acdeg',
        3 => 'acdfg',
        4 => 'bcdf',
        5 => 'abdfg',
        6 => 'abdefg',
        7 => 'acf',
        8 => 'abcdefg',
        9 => 'abcdfg',
    ];

    /**
     * @param $inputFile string
     * @return Reading[]
     */
    private function parseReadings(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $matches = [];
                if (preg_match('/(.*) \| (.*)/', $line, $matches)) {
                    $result[] = new Reading(explode(" ", $matches[1]), explode(" ", $matches[2]));
                }
                return $result;
            }, []);
    }


    /**
     * https://adventofcode.com/2021/day/8
     * @param $inputFile
     * @return int the number of times the digits 1,4,7,8 appear in the output
     */
    public function question1($inputFile): int {
        $readings = $this->parseReadings($inputFile);
        $allowed = array_map(function($digit) {
               return strlen($digit);
        }, [self::DIGITS[1], self::DIGITS[4], self::DIGITS[7],self::DIGITS[8]]);
        return array_reduce($readings, function ($c, $reading) use($allowed) {
            /** @var $reading Reading */
            return array_reduce($reading->output, function ($c1, $digit) use ($allowed) {
                if (in_array(strlen($digit), $allowed)) {
                    $c1++;
                }
                return $c1;
            }, $c);
        }, 0);
    }

    /**
     * @param string $inputFile
     * @return int
     */
    public function question2(string $inputFile) {
        $readings = $this->parseReadings($inputFile);
        return array_reduce($readings, function($sum, $reading) {
            /** @var Reading $reading */
            $value = $reading->translate();
            echo json_encode($reading->output)."=> $value\n";
            return $sum + $value;
        }, 0);
    }

}