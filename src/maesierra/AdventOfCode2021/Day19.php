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
        foreach ($scanners as $scanner) {
            if ($scanner->isLocated()) {
                echo "scanner {$scanner->id} located at {$scanner->position}\n";
                foreach ($scanner->beacons as $beacon) {
                    if (!isset($beacons["$beacon"])) {
                        $beacons["$beacon"] = $beacon;
                    }
                }
            } else {
                echo "scanner {$scanner->id} not located\n";
            }
        }
        return count($beacons);
    }

    public function question2(string $inputFile):int {
        return 0;
    }

}