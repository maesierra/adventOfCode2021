<?php


namespace maesierra\AdventOfCode2021;


class Day25 {

    public function transform($subject, $value): int {
        return ($value * $subject) % 20201227;
    }

    public function calculateLoopSize($subject, $pk): int {
        $loopSize = 0;
        $value = 1;
        while ($pk != $value) {
            $loopSize++;
            echo "LoopSize attempt #$loopSize\n";
            $value = $this->transform($subject, $value);
        }
        return $loopSize;
    }

    public function question1(string $inputFile): int {
        $map = array_reduce(explode("\n", file_get_contents($inputFile)), function(&$res, $line) {
            $line = trim($line);
            if ($line) {
                $res[] = str_split($line);
            }
            return $res;
        }, []);
        $nColumns = count($map[0]);
        $nRows = count($map);
        $step = 0;
        do {
            $step++;
            //Move east
            $newMap = [];
            $movements = 0;
            foreach ($map as $row) {
                $newRow = $row;
                foreach ($row as $pos => $space) {
                    if ($space == '>') {
                        $next = $pos < ($nColumns - 1) ? $pos + 1 : 0;
                        if ($row[$next] == '.') {
                            $newRow[$pos] = '.';
                            $newRow[$next] = $space; //Move right for next
                            $movements++;
                        }
                    }
                }
                $newMap[] = $newRow;
            }
            //Move south
            for ($c = 0; $c < $nColumns; $c++) {
                $column = array_column($newMap, $c);
                foreach ($column as $pos => $space) {
                    if ($space == 'v') {
                        $next = $pos < ($nRows - 1) ? $pos + 1 : 0;
                        if ($column[$next] == '.') {
                            $newMap[$next][$c] = $space; //Move right for next
                            $newMap[$pos][$c] = '.';
                            $movements++;
                        }
                    }
                }
            }
            $map = $newMap;
            echo "\nstep $step---$movements----------\n";
        } while ($movements);
        echo implode("\n", array_map(function ($r) {
            return implode("", $r);
        }, $map));
        return $step;
    }
}