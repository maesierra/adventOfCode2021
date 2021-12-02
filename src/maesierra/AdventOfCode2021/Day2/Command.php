<?php

namespace maesierra\AdventOfCode2021\Day2;

abstract class Command
{
    public $command;
    public $value;

    /**
     * @param $command
     * @param $value
     */
    public function __construct($command, $value)
    {
        $this->command = $command;
        $this->value = $value;
    }

    public function __toString(): string {
        return "{$this->command} {$this->value}";
    }

    /**
     * @param $position Position
     * @return Position
     */
    public abstract function apply(Position $position): Position;


}