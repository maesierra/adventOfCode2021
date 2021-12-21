<?php


namespace maesierra\AdventOfCode2021\Day21;


class QuantumDiracDiceGame extends DiracDiceGame {

    private static $allRolls;
    private static $rollsCount;
    private static $idGen = 1;
    private static $knownOutcomes = [];
    public $player1Wins = 0;
    public $player2Wins = 0;


    public function toState($rolls) {
        return ($this->round % 2 == 0 ? "even" : "odd").":"
              .$this->player1->toState().":"
              .$this->player2->toState().":"
              .implode("+", $rolls);
    }

    /**
     * @param $player1StartingPos int
     * @param $players2StartingPos int
     * @param $maxScore
     */
    public function __construct($player1StartingPos, $players2StartingPos, $maxScore) {
        parent::__construct($player1StartingPos, $players2StartingPos, $maxScore);
        $this->dice = new DeterministicDice();
        $this->id = self::$idGen++;
    }

    private static function allRolls(): array {
        if (!self::$allRolls) {
            $allRolls = [];
            foreach ([1, 2, 3] as $r1) {
                foreach ([1, 2, 3] as $r2) {
                    foreach ([1, 2, 3] as $r3) {
                        $allRolls[] = [$r1, $r2, $r3];
                    }
                }
            }
            //Group them by sum
            $bySum = array_reduce($allRolls, function(&$res, $rolls) {
                $sum = array_sum($rolls);
                $res[$sum][] = $rolls;
                return $res;
            }, []);
            foreach ($bySum as $sum => $rollList) {
                self::$allRolls[$sum] = reset($rollList);
                self::$rollsCount[$sum] = count($rollList);
            }
        }
        return self::$allRolls;
    }

    private function outcome($rolls = []) {
        $state = $this->toState($rolls); //Capture the state before the rolls
        if ($rolls) {
            $this->playRound($rolls, false);
        }
        if (!$this->checkGameEnd()) {
            foreach (self::allRolls() as $sum => $rolls){
                //Check if we already know the outcome
                $outcome = self::$knownOutcomes[$this->toState($rolls)];
                if (!$outcome) {
                    $newCopy = clone $this; //This is a shallow copy
                    //need to clone the players
                    $newCopy->player1 = clone $this->player1;
                    $newCopy->player2 = clone $this->player2;
                    $newCopy->player1Wins = 0;
                    $newCopy->player2Wins = 0;
                    //It's a new universe
                    $newCopy->id = self::$idGen++;
                    $outcome = $newCopy->outcome($rolls);
                }
                $this->player1Wins += $outcome[0] * self::$rollsCount[$sum];
                $this->player2Wins += $outcome[1] * self::$rollsCount[$sum];
            };
        } else {
            if ($this->winner === $this->player1) {
                $this->player1Wins++;
            } else {
                $this->player2Wins++;
            }
        }
        $outcome = [$this->player1Wins, $this->player2Wins];
        self::$knownOutcomes[$state] = $outcome;
        if ($this->round <= 3) {
            echo "{$this->id} {$this->round} $state 1:{$this->player1Wins} 2:{$this->player2Wins}\n";
        }
        return $outcome;

    }

    public function run() {
        $this->outcome([]);
    }
}