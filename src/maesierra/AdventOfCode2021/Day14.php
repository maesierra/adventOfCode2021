<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;


class Day14 {

    /** @var array */
    static $cache;

    /**
     * @param $inputFile string
     * @return array template,$ruleMap
     */
    private function parseInput(string $inputFile): array {
        $input = explode("\n", trim(file_get_contents($inputFile)));
        //First line is the template
        $template = trim(array_shift($input));
        $ruleMap = array_reduce($input, function (&$result, $line) {
                $matches = [];
                if (preg_match('/^(..) -> (.)$/', $line, $matches)) {
                    list($m, $pair, $element) = $matches;
                    $result[$pair] = $element;
                }
                return $result;
            }, []);
        return [$template, $ruleMap];
    }



    public function applyRules(string $inputFile, int $steps):array {
        list($template, $rules) = $this->parseInput($inputFile);
        self::$cache = [];
        //Map with all letters used
        $map = array_reduce(array_keys($rules), function (&$h, $pair) {
            list($p1, $p2) = str_split($pair);
            $h[$p1] = 0;
            $h[$p2] = 0;
            return $h;
        });
        $res = str_split($template);
        $histogram = $this->merge($map, array_reduce($res, function (&$h, $c) {
            $h[$c] = ($h[$c] ?? 0) + 1;
            return $h;
        }, []));
        for ($pos = 1; $pos < count($res); $pos++) {
            $histogram = $this->merge($histogram, $this->expand([$res[$pos - 1], $res[$pos]], $rules, $map, $steps));
        }
        return $histogram;
    }

    private function merge($h1, $h2) {
        $h = [];
        foreach ($h1 as $letter => $count) {
            $h[$letter] = $h2[$letter] + $count;
        }
        return $h;
    }


    public function expand($pair, $rules, $map, int $steps) {
        $newElement = $rules[$pair[0].$pair[1]];
        if ($steps == 0) {
            return [];
        }
        //Increase the usage of the new element
        $histogram = $this->merge($map, [$newElement => 1]);
        //Look if new par1 is already solved for the current steps
        $key = $pair[0] . $newElement . ":" . ($steps - 1);
        $part1 = self::$cache[$key] ?? false;
        if (!$part1) {
            $part1 = $this->expand([$pair[0], $newElement], $rules, $map, $steps - 1);
            self::$cache[$key] = $part1;
        }
        $histogram = $this->merge($histogram, $part1);
        //Look if new par 2 is already solved for the current steps
        $key = $newElement . $pair[1]. ":" . ($steps - 1);
        $part2 = self::$cache[$key] ?? false;
        if (!$part2) {
            $part2 = $this->expand([$newElement, $pair[1]], $rules, $map, $steps - 1);
            self::$cache[$key] = $part2;
        }
        return $this->merge($histogram, $part2);

    }

    /**
     * https://adventofcode.com/2021/day/14
     * @param $inputFile
     * @return int quantity of the most common element minus the quantity of the least common element
     */
    public function question1($inputFile): int {
        //$histogram = $this->applyRules($inputFile, 10);
        $histogram = $this->applyRules($inputFile, 10);
        asort($histogram);
        $min = reset($histogram);
        $max = end($histogram);
        return $max - $min;
    }


    /**
     * https://adventofcode.com/2021/day/14
     * @param $inputFile
     * @return int quantity of the most common element minus the quantity of the least common element
     */
    public function question2($inputFile): int {
        $histogram = $this->applyRules($inputFile, 40);
        asort($histogram);
        $min = reset($histogram);
        $max = end($histogram);
        return $max - $min;
    }



}