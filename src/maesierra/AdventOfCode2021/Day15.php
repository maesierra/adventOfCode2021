<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;





use maesierra\AdventOfCode2021\Day15\Dijkstra;

class Day15 {

    private $distanceMap = [];
    private $map = [];
    private $nCols;
    private $nRows;


    /**
     * @param $inputFile string
     * @return int[][]
     */
    private function parseMap(string $inputFile): array {
        $this->map = array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $result[] = array_map(function ($v) {
                    return (int)$v;
                }, str_split($line));
                return $result;
            }, []);
        $this->nCols = count($this->map[0]);
        $this->nRows = count($this->map);
        return $this->map;
    }

    private function printDistanceMap() {
        return implode("\n", array_reduce($this->distanceMap, function (&$res, $row) {
            $filtered = array_values(array_filter($row, function ($v) {
                return $v > -1;
            }));
            if ($filtered) {
                $res[] = implode(" ", array_map(function($v) {
                    return sprintf("% 3d", $v);
                }, $filtered));
            }
            return $res;
        }));
    }

    private function createGraph() {
        $graph = [];
        foreach ($this->map as $r => $row) {
            foreach ($row as $c => $value) {
                $node = "$r:$c";
                foreach ($this->neighbours($r, $c) as $coords) {
                    $neighbour = "{$coords[0]}:{$coords[1]}";
                    $graph[$node][$neighbour] = $value;
                }
            }
        }
        return $graph;
    }



    /**
     * https://adventofcode.com/2021/day/15
     * @param string $inputFile
     * @return int lowest risk to reach the exit
     */
    public function question1(string $inputFile):int {
        $this->parseMap($inputFile);
        return $this->solveByDijkstra();
    }


    /**
     * https://adventofcode.com/2021/day/15
     * @param string $inputFile
     * @return int lowest risk to reach the exit on the expanded map
     */
    public function question2(string $inputFile):int {
        $this->parseMap($inputFile);
        $exp = 5;
        foreach ($this->map as $r => $row) {
            foreach ($row as $c => $value) {
                for ($i = 1; $i < $exp; $i++) {
                    $v = $value + $i;
                    if ($v > 9) {
                        $v = $v - 9;
                    }

                    $offset = $this->nCols * $i;
                    $this->map[$r][$offset + $c] = $v;
                }
            }
            ksort($this->map[$r]);
        }
        $nRows = count($this->map);
        for ($i = 1; $i < $exp; $i++) {
            for ($r = 0; $r < $nRows; $r++) {
                foreach ($this->map[$r] as $c => $value) {
                    $v = $value + $i;
                    if ($v > 9) {
                        $v = $v - 9;
                    }
                    $offset = $this->nCols * $i;
                    $this->map[$r + $offset][$c] = $v;

                }
            }
        }
        $this->nCols = count($this->map[0]);
        $this->nRows = count($this->map);
        return $this->solveByDijkstra();
    }


    private function calculateDistances($mapSize) {
        $start = $this->nCols - $mapSize;
        for ($r = $start; $r < $this->nRows; $r++) {
            for ($c = $start; $c < $this->nCols; $c++) {
                if ($this->distanceMap[$r][$c] == -1) {
                    $this->findLowest($r, $c, $start);
                }
            }
        }
    }

    private function findLowest($row, $col, $mapBoundary, $prev = []) {
        if ($this->distanceMap[$row][$col] != -1) {
            return $this->distanceMap[$row][$col];
        }
        $min = PHP_INT_MAX;
        $found = false;
        $neighbours = $this->neighbours($row, $col, $mapBoundary);
        foreach ($neighbours as $coords) {
            list($r, $c) = $coords;
            if ($r == $this->nRows - 1 && $c == $this->nCols - 1) {
                $value = $this->map[$row][$col] + $this->map[$r][$c];
                $this->distanceMap[$row][$col] = $value;
                if ($this->distanceMap[$r][$c] == -1) {
                    $this->distanceMap[$r][$c] = $this->map[$r][$c];
                }
                return $value;
            } elseif (count(array_keys($prev,"$r:$c" )) == 2) {
                continue;
            }
            $lowest = $this->findLowest($r, $c, $mapBoundary, array_merge($prev, ["$r:$c"]));
            if ($lowest < $min) {
                $min = $lowest;
                $found = true;
            }
        }
        if ($found) {
            $value = $this->map[$row][$col] + $min;
            $this->distanceMap[$row][$col] = $value;
            return $value;
        }
        return  -1;
    }

    /**
     * @param int $row
     * @param int $col
     * @param $limit
     * @return array
     */
    private function neighbours(int $row, int $col, $limit = 0): array {
        return array_reduce(
            [[$row - 1, $col], [$row, $col + 1], [$row + 1, $col], [$row, $col - 1]],
            function (&$res, $coords) use($limit) {
                list($r, $c) = $coords;
                if ($r < $limit || $r >= $this->nRows || $c < $limit || $c >= $this->nCols) {
                    return $res;
                } else {
                    $res[] = $coords;
                    return $res;
                }
            },
            []
        );
    }

    /**
     * @return mixed
     */
    private function solveByDistanceMap()
    {
        foreach ($this->map as $r) {
            $this->distanceMap[] = array_fill(0, $this->nCols, -1);
        }
        for ($distance = 2; $distance <= $this->nCols; $distance++) {
            $this->calculateDistances($distance);
        }
        echo "\n\n" . $this->printDistanceMap() . "\n\n";
        return $this->distanceMap[0][0] - $this->map[0][0];
    }

    /**
     * @return mixed
     */
    private function solveByDijkstra()
    {
        $graph = $this->createGraph();
        $solver = new Dijkstra($graph);
        $dest = ($this->nRows - 1) . ":" . ($this->nCols - 1);
        $path = $solver->shortestPath("0:0", $dest);
        return array_reduce($path, function ($sum, $node) {
            list($r, $c) = explode(":", $node);
            if ($r == 0 && $c == 0) {
                return $sum;
            }
            return $sum + $this->map[$r][$c];
        }, 0);
    }
}