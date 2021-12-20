<?php


namespace maesierra\AdventOfCode2021\Day19;


class Position {

    public $x;
    public $y;
    public $z;

    /**
     * Position constructor.
     * @param $x
     * @param $y
     * @param $z
     */
    public function __construct($x, $y, $z) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function __toString() {
        return "{$this->x}:{$this->y}:{$this->z}";
    }

    /**
     * @param $offset Offset
     * @return Position
     */
    public function offset(Offset $offset) {
        return new Position(
            $this->x + $offset->x,
            $this->y + $offset->y,
            $this->z + $offset->z
        );
    }

}