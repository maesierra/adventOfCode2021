<?php


namespace maesierra\AdventOfCode2021\Day21;


abstract class DiracDiceGame {

    /** @var Player */
    public $player1;
    /** @var Player */

    public $player2;

    public $round = 0;
    /**  @var Player */
    public $winner;
    /**  @var Player */
    public $looser;

    public $maxScore;

    public $id = "game";

    /**
     * DiracDiceGame constructor.
     * @param $player1StartingPos int
     * @param $players2StartingPos int
     * @param $maxScore
     */
    public function __construct($player1StartingPos, $players2StartingPos, $maxScore) {
        $this->player1 = new Player("1", $player1StartingPos);
        $this->player2 = new Player("2", $players2StartingPos);
        $this->maxScore = $maxScore;
    }


    /**
     * @return int[]
     */
    protected function roll() {
        return [0, 0, 0];
    }


    public function run() {
        if ($this->winner) {
            return; //Already finished
        }
        while (!$this->checkGameEnd()) {
            $rolls = $this->roll();
            $this->playRound($rolls);
        }
        echo "{$this->id} {$this->winner} wins\n";
    }

    /**
     * @return bool
     */
    protected function checkGameEnd(): bool {
        if ($this->player1->score >= $this->maxScore) {
            $this->winner = $this->player1;
            $this->looser = $this->player2;
            return true;
        } else if ($this->player2->score >= $this->maxScore) {
            $this->winner = $this->player2;
            $this->looser = $this->player1;
            return true;
        }
        return false;
    }

    /**
     * @return Player
     */
    private function currentPlayer(): Player {
        return ($this->round % 2 == 0) ? $this->player1 : $this->player2;
    }

    /**
     * @param array $rolls
     */
    protected function playRound(array $rolls, $verbose = true): void
    {
        $player = $this->currentPlayer();
        $player->move($rolls);
        if ($verbose) {
            echo "{$this->id} $player rolls ".implode("+", $rolls)." and moves to space {$player->position} for a total score of {$player->score}.\n";
        }
        $this->round++;
    }


}