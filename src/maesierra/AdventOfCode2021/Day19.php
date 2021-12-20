<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




use maesierra\AdventOfCode2021\Day19\Position;
use maesierra\AdventOfCode2021\Day19\Scanner;

class Day19 {


    /**
     * @param string $inputFile
     * @return Position[] scanner id => position
     */
    private function readPositions(string $inputFile) {
        return array_reduce(explode("\n", file_get_contents($inputFile.".additional")),
            function (&$result, $line) {
                /** @var $result Position[] */
                $line = trim($line);
                if ($line) {
                    if (preg_match('/^scanner (\d+) located at (-?\d+):(-?\d+):(-?\d+)$/', $line, $matches)) {
                        $result[$matches[1]] = new Position($matches[2], $matches[3], $matches[4]);
                    } return $result;
                }
                return $result;
            }
            , []
        );
    }

    /**
     * Reads the input file and splits it into rules and messages
     * @param string $inputFile
     * @return Scanner[]
     */
    private function readScanners(string $inputFile): array {
        $scanners = array_reduce(explode("\n", file_get_contents($inputFile)),
            function (&$result, $line) {
                /** @var $result Scanner[] */
                $line = trim($line);
                if ($line) {
                    if (preg_match('/^--- scanner (\d+) ---$/', $line, $matches)) {
                        $result[] = new Scanner($matches[1]);
                    } else {
                        list($x, $y, $z) = explode(",", $line);
                        $result[count($result) - 1]->addBeacon($x, $y, $z);
                    }
                    return $result;
                }
                return $result;
            }, []);
        return array_reduce($scanners, function(&$res, $scanner) {
            $res[$scanner->id] = $scanner;
            return $res;
        }, []);
    }




    public function question1(string $inputFile):int {
        $scanners = $this->readScanners($inputFile);
        //Scanner 0 it's our reference
        $s0 = $scanners["0"];
        $s0->position = new Position(0, 0, 0);
        unset($scanners["0"]); //We can ignore scanner 0 from the rest of the calculations
        $next = [$s0];
        do {
            $located = $next;
            $next = [];
            foreach ($located as $referenceScanner) {
                foreach ($scanners as $scanner) {
                    if ($scanner->isLocated()) {
                        continue;
                    }
                    $offset = $referenceScanner->overlaps($scanner);
                    if ($offset) {
                        $scanner->position = new Position($offset->x, $offset->y, $offset->z);
                        //Remap the beacons to the reference
                        $scanner->setBeacons(array_reduce($scanner->beacons, function(&$res, $pos) use($offset) {
                            $pos = $offset->apply($pos);
                            $res["$pos"] = $pos;
                            return $res;
                        }, []));
                        $next[] = $scanner;
                    }
                }
            }
        } while ($next);

        $beacons = $s0->beacons;
        $output = '';
        foreach ($scanners as $scanner) {
            if ($scanner->isLocated()) {
                $str = "scanner {$scanner->id} located at {$scanner->position}\n";
                echo $str;
                $output .= $str;
                foreach ($scanner->beacons as $beacon) {
                    if (!isset($beacons["$beacon"])) {
                        $beacons["$beacon"] = $beacon;
                    }
                }
            } else {
                echo "scanner {$scanner->id} not located\n";
            }
        }
        file_put_contents($inputFile.".additional", $output);
        return count($beacons);
    }

    public function question2(string $inputFile):int {
        $scanners = $this->readScanners($inputFile);
        $positions = $this->readPositions($inputFile); //Read the positions created running question 1
        foreach ($scanners as $scanner) {
            $scanner->position = ($scanner->id != "0") ? $positions[$scanner->id] : new Position(0, 0, 0);
        }
        $max = 0;
        foreach ($scanners as $s1) {
            foreach ($scanners as $s2) {
                if ($s1 !== $s2) {
                    $max = max($max, $s1->position->manhattanDistance($s2->position));
                }
            }
        }
        return $max;
    }

}