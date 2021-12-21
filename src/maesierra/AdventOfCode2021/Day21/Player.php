<?php


namespace maesierra\AdventOfCode2021\Day21;


class Player {

    /** @var string  */
    public $id;
    /** @var int  */
    public $position;
    /** @var int  */
    public $score;

    /**
     * Player constructor.
     * @param $id string
     * @param $position int
     */
    public function __construct($id, $position) {
        $this->id = $id;
        $this->position = $position;
        $this->score = 0;
    }
    
    public function __toString() {
        return "player {$this->id}";
    }

    /**
     * Moves the player by the given roll
     */
    public function move($rolls):int {
        foreach ($rolls as $roll) {
            $this->position = $this->position + $roll;
            if ($this->position > 10) {
                $this->position = $this->position % 10;
                if ($this->position == 0) {
                    $this->position = 10;
                }
            }
        }
        $this->score += $this->position;
        return $this->score;
    }

    public function toState() {
        return "{$this->position}:{$this->score}";
    }


}