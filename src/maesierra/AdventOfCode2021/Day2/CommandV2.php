<?php

namespace maesierra\AdventOfCode2021\Day2;

class CommandV2 extends Command
{

    /**
     * @param $position Position
     * @return Position
     */
    public function apply(Position $position): Position {
        switch ($this->command) {
            case 'up':
                $position->aim -= $this->value;
                break;
            case 'down':
                $position->aim += $this->value;
                break;
            case 'forward':
                $position->hpos += $this->value;
                $position->depth += $this->value * $position->aim;
        }
        return $position;
    }


}