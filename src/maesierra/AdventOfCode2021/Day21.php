<?php


namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day21\DeterministicDice;
use maesierra\AdventOfCode2021\Day21\DeterministicDiracDiceGame;
use maesierra\AdventOfCode2021\Day21\QuantumDiracDiceGame;

class Day21 {

    /**
     * @param string $inputFile
     * @return int[] player id => starting positions
     */
    private function readStartingPositions(string $inputFile) {
        return array_reduce(explode("\n", file_get_contents($inputFile)),
            function (&$result, $line) {
                $line = trim($line);
                if ($line) {
                    if (preg_match('/^Player (\d+) starting position: (\d+)$/', $line, $matches)) {
                        $result[$matches[1]] = (int)$matches[2];
                    } return $result;
                }
                return $result;
            }
            , []
        );
    }


    public function question1(string $inputFile):int {
        $positions = $this->readStartingPositions($inputFile);
        $game = new DeterministicDiracDiceGame($positions[1], $positions[2], 1000);
        $game->run();
        return $game->dice->count * $game->looser->score;
    }

    public function question2(string $inputFile):string {
        $positions = $this->readStartingPositions($inputFile);
        $game = new QuantumDiracDiceGame($positions[1], $positions[2], 21);
        $game->run();;
        return max($game->player1Wins, $game->player2Wins);
    }




}