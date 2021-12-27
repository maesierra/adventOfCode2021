<?php


namespace maesierra\AdventOfCode2021\Day23;


class Hallway {

    /** @var CorridorPosition[] */
    public $positions = [];

    /** @var CorridorPosition[] */
    public $noExit = [];

    /** @var Location[] */
    public $locations = [];

    /** @var Room[] */
    public $rooms;

    /** @var Location[] */
    public $amphipodsLocation = [];

    /**
     * Hallway constructor.
     *
     * #############
     * #...........#
     *  ###A#B#C#D###
     *
     * @param $rooms Room[]
     * @param $positions CorridorPosition[]
     */

    public function __construct($rooms, $positions = []) {
        if (!$positions) {
            for ($i = 0; $i < 11; $i++) {
                if (!in_array($i, [2, 4, 6, 8])) {
                    $this->positions[$i] = new CorridorPosition();
                } else {
                    $this->positions[$i] = new ExitCorridorPosition();
                }
                $this->positions[$i]->position = $i;
            }
        } else {
            $this->positions = $positions;
        }
        foreach ($this->positions as $i => $position) {
            if (!in_array($i, [2, 4, 6, 8])) {
                $this->noExit[] = $position;
            }
        }
        $this->rooms = $rooms;
        $this->locations = array_merge($this->positions, $this->rooms);
        foreach ($this->locations as $location) {
            foreach ($location->occupants() as $amphipod) {
                $this->amphipodsLocation[$amphipod->id] = $location;
            }
        }
    }
    /**
     * @param $amphipod Amphipod
     * @return Location
     */
    public function locate($amphipod) {
        return $this->amphipodsLocation[$amphipod->id] ?? null;
    }

    /**
     * @param $amphipod Amphipod
     * @param $destination Location
     * @return Hallway
     */
    public function move($amphipod, $destination): Hallway {
        $rooms = array_map(function ($r) {
            return clone $r;
        }, $this->rooms);
        $positions = array_map(function ($r) {
            return clone $r;
        }, $this->positions);
        //Copy the hallway
        $hallway = new Hallway($rooms, $positions);
        $hallway->locate($amphipod)->remove($amphipod);
        $hallway->destination($destination->id())->moveIn($amphipod);
        $hallway->amphipodsLocation[$amphipod->id] = $destination;
        return $hallway;
    }

    /**
     * @param $l1 Location
     * @param $l2 Location
     * @return int
     */
    public function distance($l1, $l2): int {
        $h1 = $l1->position;
        $h2 = $l2->position;
        if ($l1 instanceof Room) {
            //The exit of a room is r->position * 2 + 2
            $h1 = ($h1 * 2) + 2;
        }
        if ($l2 instanceof Room) {
            $h2 = ($h2 * 2) + 2;
        }
        return abs($h2 - $h1);
    }



    /**
     * @param $id
     * @return Location
     */
    private function destination($id) {
        foreach ($this->locations as $location) {
            if ($location->id() == $id) {
                return $location;
            }
        }
        return null;
    }

    /**
     * @return Room
     */
    public function roomForType($type) {
        foreach ($this->rooms as $room) {
            if ($room->type == $type) {
                return $room;
            }
        }
        return null;
    }

    /**
     * @param $type string
     * @return Amphipod[]
     */
    public function locateByType($type, $includeRooms = true, $includeCorridorPositions = true) {
        $amphipods = [];
        if ($includeRooms) {
            $amphipods = array_reduce($this->rooms, function($res, $room) use($type) {
                /** @var Room $room */
                return array_filter(array_merge($res, $room->availableToMove()), function ($a) use($type) {
                    return $a->type == $type;
                });
            }, []);
        }
        if ($includeCorridorPositions) {
            $amphipods = array_reduce($this->positions, function (&$res, $position) use($type) {
                /** @var CorridorPosition $position */
                if ($position->occupant && $position->occupant->type == $type) {
                    $res[] = $position->occupant;
                }
                return $res;
            }, $amphipods);
        }
        return $amphipods;
    }

    /**
     * @param $amphipod Amphipod
     * @param $to Room|CorridorPosition|Location
     * @param int $additonalBlock
     * @return bool
     */
    public function isPathBlocked($amphipod, $to, $additonalBlock = -1) {
        /** @var Room|Location $from */
        $from = $this->locate($amphipod);
        if ($from instanceof Room && !$from->canMoveOut($amphipod)) {
            return true; //It's blocked inside the room
        }
        if ($from->id() == $to->id()) {
            return true;
        }
        $h1 = $this->positions[$from->exitPosition()];
        $h2 = $this->positions[$to->exitPosition()];
        $direction = $h1->position > $h2->position ? -1 : 1;
        for ($pos = $h1->position + $direction; $pos != $h2->position; $pos += $direction) {
            if ($this->positions[$pos]->occupant || $pos == $additonalBlock) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return Amphipod[]
     */
    public function availableToMove():array {
        $amphipods = array_reduce($this->rooms, function($res, $room) {
            /** @var Room $room */
            return array_merge($res, $room->availableToMove());
        }, []);
        return array_merge($amphipods, $this->inCorridor());
    }

    /**
     * @return int the cost of moving all amphipods to their destination room (ignoring blocked paths)
     */
    public function minimumToSolve() {
        $cost = 0;
        foreach ($this->locations as $location) {
            foreach ($location->occupants() as $amphipod) {
                $destination = $this->roomForType($amphipod->type);
                if ($destination->id() != $location->id()) {
                    $cost =+ $amphipod->cost($location, $destination);
                }
            }
        }
        return $cost;
    }


    public function completed():bool {
        return array_reduce($this->rooms, function($b, $room) {
            /** @var Room $room */
            return $b && $room->completed();
        }, true);
    }

    public function __toString() {
        $hall = implode("", array_map(function($p) {
            return $p->occupant ? $p->occupant->type : '.';
        }, $this->positions));
        $str = "#############\n".
               "#". $hall ."#\n";
        for ($i = 0; $i < $this->rooms[0]->size; $i++) {
            $r = implode("#", array_map(function($r) use($i) {
                $amphipod = $r->spaces[$i];
                return $amphipod ? $amphipod->type : '.';
            }, $this->rooms));
            $str.= $i == 0 ?
                "###".$r."###\n" :
                "  #".$r."#  \n" ;
        }
        return $str.
               "  #########  \n";
    }

    /**
     * @return Amphipod[]
     */
    public function getAmphipods() {
        return array_reduce($this->locations, function ($res, $l) {
            /** @var $l Location */
            return array_merge($res, $l->occupants());
        }, []);
    }

    public function toState():string {
        $amphipods = [];
        foreach ($this->locations as $location) {
            foreach ($location->occupants() as $amphipod) {
                $amphipods[] = $amphipod->id.":".$location->id();
            }
        }
        sort($amphipods);
        return implode(":", $amphipods);
    }

    /**
     * @return Amphipod[]
     */
    public function inCorridor(): array
    {
        return array_reduce($this->positions, function (&$res, $position) {
            /** @var CorridorPosition $position */
            if ($position->occupant) {
                $res[] = $position->occupant;
            }
            return $res;
        }, []);
    }

}