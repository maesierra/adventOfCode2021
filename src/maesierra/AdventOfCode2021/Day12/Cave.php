<?php


namespace maesierra\AdventOfCode2021\Day12;


class Cave {

    public $name;
    /** @var Cave[] */
    public $connections;

    /**
     * Cave constructor.
     * @param $name
     */
    public function __construct($name) {
        $this->name = $name;
        $this->connections = [];
    }

    public function __toString() {
        return $this->name;
    }


    public function link(Cave $other) {
        if (!isset($this->connections[$other->name])) {
            $this->connections[$other->name] = $other;
        }
    }
}