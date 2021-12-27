<?php


namespace maesierra\AdventOfCode2021\Day23;


class Room extends Location {

    /**
     * @var array
     */
    public $spaces = [];

    /** @var Amphipod */
    public $space1;

    /** @var Amphipod */
    public $space2;
    /** @var string */
    public $type;

    public $space1Locked =  false;
    public $space2Locked =  false;

    /**
     * Room constructor.
     * @param $position
     */
    public function __construct($position, $type, $size = 2) {
        $this->position = $position;
        $this->type = $type;
    }


    public function contains(Amphipod $amphipod): bool {
        return ($this->containsInPosition1($amphipod)) ||
               ($this->containsInPosition2($amphipod));

    }

    public function remove(Amphipod $amphipod): bool {
        if  ($this->containsInPosition1($amphipod))  {
            $this->space1 = null;
            return true;
        } else if  ($this->containsInPosition2($amphipod)) {
            $this->space2 = null;
            return true;
        }
        return false;
    }

    public function moveIn(Amphipod $amphipod) {
        if ($this->isEmpty()) {
            $this->space2 = $amphipod;
            $this->space2Locked = true;
        } else {
            $this->space1 = $amphipod;
            $this->space1Locked = true;
        }

    }


    public function id() {
        return "R{$this->position}";
    }

    public function accepts(Amphipod $amphipod):bool {
        return ($this->isEmpty() || $this->isHalfCompleted()) && $amphipod->type == $this->type;
    }

    /**
     * @return Amphipod[]
     */
    public function availableToMove() {
        $res = [];
        if ($this->space1 && !$this->space1Locked) {
            $res[] = $this->space1;
        }
        if (!$this->space1 && $this->space2 && !$this->space2Locked) {
            $res[] = $this->space2;
        }
        return $res;
    }

    public function isEmpty() {
        return !$this->space1 && !$this->space2;
    }

    /**
     * @return bool
     */
    public function isFull(): bool {
        return $this->space1 && $this->space2;
    }

    public function isHalfCompleted():bool {
        return !$this->isFull() && $this->space2 && $this->space2->type == $this->type;
    }

    public function exitPosition(): int {
        //The exit of a room is r->position * 2 + 2
        return ($this->position * 2) + 2;
    }

    public function completed():bool {
        return $this->isFull() && $this->space1->type == $this->type && $this->space2->type == $this->type;
    }

    public function add(Amphipod $amphipod) {
        if ($this->space1) {
            $this->space2 = $amphipod;
            if ($amphipod->type == $this->type) {
                $this->space2Locked = true;
            }
        } else {
            $this->space1 = $amphipod;
        }
    }

    /**
     * @param Amphipod $a
     * @return Amphipod|null
     */
    public function otherOccupant(Amphipod $a) {
        if (!$this->isFull() || !$this->contains($a)) {
            return null;
        }
        return $this->space1->id != $a->id ? $this->space2 : $this->space1;
    }

    /**
     * @return Amphipod[]
     */
    public function occupants(): array {
        $occupants = [];
        if ($this->space1) {
            $occupants[] = $this->space1;
        }
        if ($this->space2) {
            $occupants[] = $this->space2;
        }
        return $occupants;
    }

    public function innerDistance(Amphipod $amphipod): int {
        if ($this->contains($amphipod)) {
            //Exiting the room
            return $this->containsInPosition2($amphipod) ? 2 : 1;
        } else {
            //Entering the room
            return $this->isEmpty() ? 2 : 1;
        }
    }

    /**
     * @param Amphipod $amphipod
     * @return bool
     */
    public function containsInPosition1(Amphipod $amphipod): bool {
        return $this->space1 && $this->space1->id == $amphipod->id;
    }

    /**
     * @param Amphipod $amphipod
     * @return bool
     */
    public function containsInPosition2(Amphipod $amphipod): bool {
        return $this->space2 && $this->space2->id == $amphipod->id;
    }

    /**
     * @param $position
     * @return Amphipod
     */
    public function getSpace($position) {
        return $position == 1 ? $this->space1 : $this->space2;
    }
}