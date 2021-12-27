<?php


namespace maesierra\AdventOfCode2021\Day23;


abstract class Location {

    public $position;

    public abstract function contains(Amphipod $amphipod):bool;

    public abstract function remove(Amphipod $amphipod):bool;

    public abstract function moveIn(Amphipod $amphipod);

    public abstract function id();

    public abstract function exitPosition():int;

    public abstract function innerDistance(Amphipod $amphipod):int;

    /**
     * @return Amphipod[]
     */
    public abstract function occupants():array;

    /**
     * @param Location $location
     * @return int
     */
    public function distance(Amphipod $amphipod, Location $location): int {
        return abs($this->exitPosition() - $location->exitPosition()) + $this->innerDistance($amphipod) + $location->innerDistance($amphipod);
    }
}