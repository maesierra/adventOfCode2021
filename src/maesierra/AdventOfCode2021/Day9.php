<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




use Closure;
use maesierra\AdventOfCode2021\Day9\Basin;
use maesierra\AdventOfCode2021\Day9\Point;

class Day9 {


    /**
     * @param $inputFile string
     * @return int[][]
     */
    private function parseMap(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $result[] = array_map(function($v) {
                    return (int) $v;
                }, str_split($line));
                return $result;
            }, []);
    }


    /**
     * @param array $map
     * @param int $row
     * @param int $column
     * @param $filter Closure
     * @return int[] all the adjacent rows that meet the criteria
     */
    public static function adjacent(array $map, int $row, int $column, $filter = null) {
        return array_reduce(
            [[$row - 1, $column], [$row, $column + 1], [$row + 1, $column], [$row, $column - 1]],
            function(&$res, $point) use ($map, $filter) {
                list($r, $c) = $point;
                $rowArray = $map[$r] ?? [];
                if (isset($rowArray[$c])) {
                    $value = $map[$r][$c];
                    if ($filter && $filter($value)) {
                        $res[] = $value;
                    }
                }
                return $res;
            },
            []
        );
    }

    private function lowPoints($map): array {
        $points = [];
        foreach ($map as $y => $row) {
            foreach ($row as $x => $height) {
                $lower = self::adjacent($map, $y, $x, function($v) use($height) {
                    return $v <= $height;
                });
                if (!$lower) {
                    echo "Low point found at $x,$y => $height\n";
                    $points[] = new Point($x, $y, $height);
                }
            }
        }
        return $points;
    }


    /**
     * https://adventofcode.com/2021/day/9
     * @param $inputFile
     * @return int The sum of the risk levels of all low points in the heightmap
     */
    public function question1($inputFile): int {
        $map = $this->parseMap($inputFile);
        return array_reduce($this->lowPoints($map), function($risk, $p) {
            /** @var Point $p */
            return $risk + 1 + $p->height;
        }, 0);
    }

    /**
     * https://adventofcode.com/2021/day/9
     * @param string $inputFile
     * @return int The product of the 3 largest basins
     */
    public function question2(string $inputFile):int {
        $map = $this->parseMap($inputFile);
        $basins = array_map(function($p) use($map) {
            /** @var Point $p */
            return new Basin($p, $map);
        }, $this->lowPoints($map));
        usort($basins, function($b1, $b2) {
            /** @var Basin $b1 */
            /** @var Basin $b2 */
            return $b2->size() <=> $b1->size();
        });
        return $basins[0]->size() * $basins[1]->size() * $basins[2]->size();
    }
}