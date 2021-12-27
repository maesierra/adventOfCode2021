<?php


namespace maesierra\AdventOfCode2021\Day23;


class Amphipod {

    private static $idGen = 1;

    public $type;

    public $id;

    public $cost;

    /**
     * Amphipod constructor.
     * @param $type
     */
    public function __construct($type) {
        $this->type = $type;
        $this->id = $type.self::$idGen++;
        switch ($this->type) {
            case 'A':
                $this->cost = 1;
                break;
            case 'B':
                $this->cost = 10;
                break;
            case 'C':
                $this->cost = 100;
                break;
            default:
                $this->cost = 1000;
        }
    }

    /**
     * @param Location $from
     * @param Location $to
     * @return int
     */
    public function cost(Location $from, Location $to):int {
        return $from->distance($this, $to) * $this->cost;
    }

    public function __toString() {
        return $this->id;
    }


}