<?php


namespace maesierra\AdventOfCode2021\Day23;


class Room extends Location {

    /**
     * @var Amphipod[]
     */
    public $spaces = [];

    /** @var string */
    public $type;

    /** @var int  */
    private $nextFree = 0;
    /** @var int  */
    private $firstOccupied;

    /** @var int */
    public $size;

    /** @var bool[] */
    public $locked = [];

    /**
     * Room constructor.
     * @param $position
     */
    public function __construct($position, $type, $size = 2) {
        $this->position = $position;
        $this->type = $type;
        $this->spaces = array_fill(0, $size, null);
        $this->locked = array_fill(0, $size, false);
        $this->size = $size;
        $this->nextFree = $size - 1;
        $this->firstOccupied = $size;
    }


    public function contains(Amphipod $amphipod): bool {
        return $this->locate($amphipod) != -1;
    }

    public function remove(): ?Amphipod {
        if ($this->isEmpty()) {
            return null;
        }
        $toRemove = $this->firstOccupied;
        $occupant = $this->spaces[$toRemove] ?? null;
        if (!$occupant) {
            return null;
        }
        if ($this->locked[$toRemove]) {
            return null;
        }
        $this->spaces[$toRemove] = null;
        $this->nextFree = $toRemove;
        $this->firstOccupied++;
        return $occupant;
    }

    public function moveIn(Amphipod $amphipod) {
        if ($this->isFull()) {
            return;
        }
        $this->spaces[$this->nextFree] = $amphipod;
        $this->locked[$this->nextFree] = true;
        $this->nextFree--;
        $this->firstOccupied--;

    }


    public function id() {
        return "R{$this->position}";
    }

    public function accepts(?Amphipod $amphipod):bool {
        if (!$amphipod) {
            return false;
        }
        if ($amphipod->type != $this->type || $this->isFull()) {
            return false;
        } else if ($this->isEmpty()) {
            return true;
        } else {
            return $this->locked[$this->firstOccupied];
        }
    }

    /**
     * @return Amphipod[]
     */
    public function availableToMove() {
        return $this->isEmpty() ? [] : (!$this->locked[$this->firstOccupied] ? [$this->spaces[$this->firstOccupied]] : []);
    }

    public function isEmpty() {
        return $this->nextFree == $this->size - 1;
    }

    /**
     * @return bool
     */
    public function isFull(): bool {
        return $this->firstOccupied == 0;
    }

    public function exitPosition(): int {
        //The exit of a room is r->position * 2 + 2
        return ($this->position * 2) + 2;
    }

    public function completed():bool {
        return count(array_filter($this->spaces, function($a) {
            return !$a || $a->type != $this->type;
        })) == 0;
    }

    public function add(Amphipod $amphipod) {
        $this->moveIn($amphipod);
        //Keep the lock only if all previous are already locked
        $locked = true;
        for ($pos = $this->firstOccupied; $pos < $this->size; $pos++) {
            if ($this->spaces[$pos]->type != $this->type) {
                $locked = false;
                break;
            }
        }
        $this->locked[$this->firstOccupied] = $locked;
    }

    /**
     * @param Amphipod $a
     * @return Amphipod[]
     */
    public function otherOccupants(Amphipod $other) {
        return  array_values(array_filter($this->spaces, function ($a) use($other) {
            return $a && $a->id != $other->id;
        }));
    }

    /**
     * @return Amphipod[]
     */
    public function occupants(): array {
        return array_values(array_filter($this->spaces));
    }

    public function innerDistance(Amphipod $amphipod): int {
        $pos = $this->locate($amphipod);
        if ($pos != -1) {
            //Exiting the room
            return $pos + 1;
        } else {
            //Entering the room
            return $this->nextFree + 1;
        }
    }

    /**
     * @param Amphipod $amphipod
     * @return bool
     */
    public function canMoveOut(Amphipod $amphipod): bool {
        $pos = $this->locate($amphipod);
        return $pos != -1 && $pos == $this->firstOccupied;
    }


    /**
     * @param Amphipod $amphipod
     * @return int|string
     */
    private function locate(Amphipod $amphipod): int {
        foreach ($this->spaces as $p => $a) {
            if ($a && $amphipod->id == $a->id) {
                return $p;
            }
        }
        return -1;
    }
}