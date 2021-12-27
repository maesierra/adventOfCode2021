<?php


namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day23\Amphipod;
use maesierra\AdventOfCode2021\Day23\ExitCorridorPosition;
use maesierra\AdventOfCode2021\Day23\Hallway;
use maesierra\AdventOfCode2021\Day23\CorridorPosition;
use maesierra\AdventOfCode2021\Day23\Location;
use maesierra\AdventOfCode2021\Day23\Movement;
use maesierra\AdventOfCode2021\Day23\Room;

class Day23 {

    private static $bestSolution = PHP_INT_MAX;

    private static $found = 0;

    private static $progress = 0;

    private static $progressMessage = 0;

    /**
     * @param string $inputFile
     * @return Amphipod[][]
     */
    private function readAmphipods(string $inputFile)
    {
        /** @var Amphipod[][] $amphipods */
        return array_reverse(array_reduce(explode("\n", file_get_contents($inputFile)),
            function (&$result, $line) {
                $line = trim($line);
                if ($line) {
                    if (preg_match('/#([A-D])#([A-D])#([A-D])#([A-D])#/', $line, $matches)) {
                        $row = [];
                        $row[] = new Amphipod($matches[1]);
                        $row[] = new Amphipod($matches[2]);
                        $row[] = new Amphipod($matches[3]);
                        $row[] = new Amphipod($matches[4]);
                        $result[] = $row;
                    }
                    return $result;
                }
                return $result;
            }
            , []
        ));
    }

    /**
     * @param $amphipods Amphipod[][]
     * @param int $roomSize
     * @return Hallway
     */
    private function createHallway($amphipods, $roomSize = 2) {
        $rooms = [
            new Room(0, 'A', $roomSize),
            new Room(1, 'B', $roomSize),
            new Room(2, 'C', $roomSize),
            new Room(3, 'D', $roomSize)
        ];
        foreach ($amphipods as $row) {
            foreach ($row as $pos => $amphipod) {
                $rooms[$pos]->add($amphipod);
            }
        }

        return new Hallway($rooms);
    }

    public function question1(string $inputFile):string {
        $amphipods = $this->readAmphipods($inputFile);
        $hallway = $this->createHallway($amphipods, 2);
        return Movement::totalCost($this->getBestOption($hallway));
    }

    public function question2(string $inputFile):string {
        $amphipods = $this->readAmphipods($inputFile);
        $amphipods = [
            $amphipods[0],
            [new Amphipod('D'), new Amphipod('B'), new Amphipod('A'), new Amphipod('C')],
            [new Amphipod('D'), new Amphipod('C'), new Amphipod('B'), new Amphipod('A')],
            $amphipods[1],
        ];
        $hallway = $this->createHallway($amphipods, 4);
        return Movement::totalCost($this->getBestOption($hallway));
    }

    /**
     * @param $hallway Hallway
     * @param $amphipod Amphipod
     * @return Room[]
     */
    private function availableRooms($hallway, $amphipod) {
        return  array_filter($hallway->rooms, function($room) use ($amphipod) {
            return $room->accepts($amphipod);
        });
    }

    /**
     * @param $hallway Hallway
     * @param $amphipod Amphipod
     * @return Location[] get all available destinations for the given amphipod sorted in the most preferrable way
     */
    private function getDestinations($hallway, $amphipod) {
        $from = $hallway->locate($amphipod);
        //If there is path to its destination room no need to consider other alternatives
        foreach ($this->availableRooms($hallway, $amphipod) as $r) {
           if ($r->type == $amphipod->type && !$hallway->isPathBlocked($amphipod, $r)) {
               return [$r];
           }
        };
        /** @var CorridorPosition[] $destinations */
        $destinations = [];
        //Only room to hallway movement is allowed
        if ($from instanceof Room) {
            //amphipods cannot pass each other so we only count
            //positions until we found one that it's ocuppied
            $positions = [];
            for ($i = $from->exitPosition(); $i >= 0 ; $i--) {
               if ($hallway->positions[$i]->occupant) {
                   break;
               }
                if (!($hallway->positions[$i] instanceof ExitCorridorPosition)) {
                    $destinations[] = $hallway->positions[$i];
                    $positions[] = $hallway->positions[$i]->position;
                }
            }
            for ($i = $from->exitPosition(); $i < count($hallway->positions); $i++) {
                if ($hallway->positions[$i]->occupant) {
                    break;
                }
                if (!($hallway->positions[$i] instanceof ExitCorridorPosition)) {
                    $destinations[] = $hallway->positions[$i];
                    $positions[] = $hallway->positions[$i]->position;
                }
            }
            //Never block the second exit if it will block the path to the other occupant destination
            $leftOccupied = $hallway->positions[$from->exitPosition() - 1]->occupant;
            $rightOccupied = $hallway->positions[$from->exitPosition() + 1]->occupant;
            if (($leftOccupied || $rightOccupied) && !$from->isEmpty()) {
                $opposite = $from->exitPosition() + ($leftOccupied ? 1 : -1);
                $blocked = array_filter($from->otherOccupants($amphipod), function ($a) use($from, $hallway, $opposite) {
                    return $a->type != $from->type && $hallway->isPathBlocked($a, $hallway->roomForType($a->type), $opposite);
                });
                if ($blocked) {
                    $destinations = array_filter($destinations, function ($p) use($opposite, $leftOccupied) {
                        return $leftOccupied ? $p->position < $opposite : $p->position > $opposite;
                    });
                }
            }

            //Put the closets first
            usort($destinations, function ($p1, $p2) use ($from, $amphipod) {
                /** @var $p1 CorridorPosition */
                /** @var $p2 CorridorPosition */
                return $from->distance($amphipod, $p1) <=> $from->distance($amphipod, $p2);
            });

        }
        //Make sure the path is not blocked
        return array_filter($destinations, function ($destination) use ($hallway, $from, $amphipod) {
            return !$hallway->isPathBlocked($amphipod, $destination);
        });
    }



    /**
     * @param $hallway Hallway
     * @param $amphipod Amphipod
     * @param $to Location
     * @param Movement $movements previous movements
     * @return Movement[] Movements to completed of empty if no movement
     */
    private function getBestOption($hallway, $amphipod = null, $to = null, $movements = []) {
        $state = $hallway->toState();
        if ($amphipod && $to) {
            $from = $hallway->locate($amphipod);
            $cost = $amphipod->cost($from, $to);
            $movement = new Movement($amphipod->id, $from->id(), $to->id(), $cost);
            $movements[] = $movement;
            echo implode("; ", array_map(function($m) {
                return "$m";
            }, $movements))."\n";
            $totalCost = Movement::totalCost($movements);
            if ($totalCost >= self::$bestSolution) {
                echo "discarded by cost\n";
                echo "$state\n$hallway\n";
                return [];
            }
//            if ($totalCost + $hallway->minimumToSolve() >= self::$bestSolution) {
//                echo "discarded by minimum to solve\n";
//                echo "$state\n$hallway\n";
//                return [];
//            }

            $hallway = $hallway->move($amphipod, $to);
            $state= $hallway->toState();
            echo "$state\n$hallway\n";
            echo "Found: ".self::$found." ".self::$progressMessage."\n";
            if ($hallway->completed()) {
                echo "completed with \n\n".implode("\n", $movements);
                echo "\ncost {$totalCost} --------------------\n";
                self::$bestSolution = min(self::$bestSolution, $totalCost);
                self::$found++;
                return [$movement];
            }
        }
        $destinations = $this->prioritiseDestinations(array_reduce($hallway->availableToMove(), function(&$res, $amphipod) use($hallway, $movement) {
            $destinations = $this->getDestinations($hallway, $amphipod);
            foreach ($destinations as $destination) {
                $res[$amphipod->id . ":" . $destination->id()] = [$amphipod, $destination];
            }
            return $res;
        }), $hallway);
        if (!$destinations) {
            echo "no destination found\n";
            return [];
        } else {
            echo "destinations: ".implode(",", array_keys($destinations))."\n";
            /** @var Movement $bestOption */
            $bestOption = null;
            $bestCost = 0;
            foreach ($destinations as $pos => $pair) {
                if ($to == null) {
                    self::$progressMessage = "$pos (".self::$progress."/".count($destinations).")";
                    self::$progress++;
                }
                list($amphipod, $position) = $pair;
                $optionMovements = $this->getBestOption($hallway, $amphipod, $position, $movements);
                if (!$optionMovements) {
                    continue;
                }
                $totalCost = Movement::totalCost($optionMovements);
                if (!$bestOption || $totalCost < $bestCost) {
                    $bestOption = $optionMovements;
                    $bestCost = $totalCost;
                }
            }
            return $bestOption ? array_merge([$movement], $bestOption) : [];
        }
    }


    /**
     * @param $destinations array[] array of pairs containing a source amphipod
     * @param Hallway $hallway
     * @return array
     */
    private function prioritiseDestinations($destinations, Hallway $hallway):array {
        $res = $destinations;
        //All the destination rooms for the amphipods in the corridor
        $inCorridor = array_reduce($hallway->inCorridor(), function (&$res, $a) use ($hallway) {
            $res[$a->type] = true;
            return $res;
        }, []);
        $toDestination = [];
        $emptiesCorridor = [];

        //Highest priority is for those going to their destination
        //or those that empties a room to make space for an amphipod in the corridor
        foreach ($destinations as $key => $pair) {
            /** @var $amphipod Amphipod */
            /** @var $dest Room|CorridorPosition */
            list($amphipod, $dest) = $pair;
            if ($dest instanceof Room) {
                if ($dest->type == $amphipod->type) {
                    $toDestination[$key] = true;
                }
            } else {
                $from = $hallway->locate($amphipod);
                if ($from instanceof Room && isset($inCorridor[$from->type])) {
                    $emptiesCorridor[$key] = true;
                }
            }
        }

        uasort($res, function ($pair1, $pair2) use($hallway, $emptiesCorridor, $toDestination, $destinations) {
            /** @var $amphipod1 Amphipod */
            /** @var $dest1 Room|CorridorPosition */
            list($amphipod1, $dest1) = $pair1;
            /** @var $amphipod2 Amphipod */
            /** @var $dest2 Room|CorridorPosition */
            list($amphipod2, $dest2) = $pair2;
            //Highest priority is for those going to their destination
            $key1 = $amphipod1->id . ":" . $dest1->id();
            $key2 = $amphipod2->id . ":" . $dest2->id();
            /** @var Room|Location $from1 */
            $from1 = $hallway->locate($amphipod1);
            /** @var Room|Location $from2 */
            $from2 = $hallway->locate($amphipod2);
            $distance1 = $from1->distance($amphipod1, $dest1);
            $distance2 = $from2->distance($amphipod2, $dest2);
            $toDestination1 = $toDestination[$key1] ?? false;
            $toDestination2 = $toDestination[$key2] ?? false;
            $emptiesCorridor1 = $emptiesCorridor[$key1] ?? false;
            $emptiesCorridor2 = $emptiesCorridor[$key2] ?? false;
            if ($toDestination1 && !$toDestination2) {
                return -1;
            } else if ($toDestination2 && !$toDestination1) {
                return 1;
            } else if ($toDestination1 && $toDestination2) {
                return $distance1 <=> $distance2;
            }
            //See if we can make space for something in the hallway positions
            if ($emptiesCorridor1 && !$emptiesCorridor2) {
                return -1;
            } else if ($emptiesCorridor2 && !($emptiesCorridor1)) {
                return 1;
            } else if ($emptiesCorridor1 && $emptiesCorridor2) {
                //if both empty a corridor give priority if some is not full
                if (!$from1->isFull() && $from2->isFull()) {
                    return -1;
                } else if ($from1->isFull() && !$from2->isFull()) {
                    return  1;
                } else {
                    return $distance1 <=> $distance2;
                }
            }
            else  {
                return $distance1 <=> $distance2;
            }
        });
        return $res ?: [];
    }


}