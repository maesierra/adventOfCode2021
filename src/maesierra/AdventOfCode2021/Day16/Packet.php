<?php


namespace maesierra\AdventOfCode2021\Day16;


class Packet {

    const TYPE_LITERAL = 4;

    public $version;
    public $type;
    public $completed = false;
    public $value;

    public function versionSum() {
        return $this->version;
    }

    public function isCompleted() {
        return $this->completed;
    }

    public function complete() {
        $this->completed = true;
    }

}