<?php

namespace maesierra\AdventOfCode2021\Day2;

class Position {
    public $depth = 0;
    public $hpos = 0;
    public $aim = 0;

    public function __toString(): string {
        return "h: {$this->hpos} d: {$this->depth} a: {$this->aim}";
    }
}