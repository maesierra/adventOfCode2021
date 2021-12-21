<?php


namespace maesierra\AdventOfCode2021\Day21;


class DeterministicDice
{
    public $value = 1;
    public $count = 0;

    public function roll() {
        $value = $this->value;
        $this->value++;
        $this->count++;
        if ($this->value > 100) {
            $this->value = 1;
        }
        return $value;
    }
}