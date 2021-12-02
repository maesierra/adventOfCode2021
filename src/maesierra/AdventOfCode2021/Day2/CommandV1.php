<?php

namespace maesierra\AdventOfCode2021\Day2;

class CommandV1 extends Command
{

    /**
     * @param $position Position
     * @return Position
     */
    public function apply(Position $position): Position {
        switch ($this->command) {
            case 'up':
                $position->depth -= $this->value;
                break;
            case 'down':
                $position->depth += $this->value;
                break;
            case 'forward':
                $position->hpos += $this->value;
        }
        return $position;
    }


}