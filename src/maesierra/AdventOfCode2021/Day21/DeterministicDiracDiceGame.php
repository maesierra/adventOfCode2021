<?php


namespace maesierra\AdventOfCode2021\Day21;


class DeterministicDiracDiceGame extends DiracDiceGame {

    /**
     * @var DeterministicDice
     */
    public $dice;


    /**
     * @param $player1StartingPos int
     * @param $players2StartingPos int
     * @param $maxScore
     */
    public function __construct($player1StartingPos, $players2StartingPos, $maxScore) {
        parent::__construct($player1StartingPos, $players2StartingPos, $maxScore);
        $this->dice = new DeterministicDice();
    }


    /**
     * @return int[]
     */
    protected function roll() {
        return [
            $this->dice->roll(),
            $this->dice->roll(),
            $this->dice->roll()
        ];
    }

}