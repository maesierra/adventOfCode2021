<?php


namespace maesierra\AdventOfCode2021\Day19;


class Offset {

    public $x;
    public $y;
    public $z;

    /** @var Orientation */
    public $orientation;

    /**
     * Offset constructor.
     * @param $x
     * @param $y
     * @param $z
     */
    public function __construct($x, $y, $z, $orientation) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
        $this->orientation = $orientation;
    }

    public function apply(Position $position):Position {
        list($x, $y, $z) = $this->orientation->apply($position->x, $position->y, $position->z, $this->x, $this->y, $this->z);
        return new Position($x, $y, $z);
    }

}