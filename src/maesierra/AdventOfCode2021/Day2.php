<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day2\Command;
use maesierra\AdventOfCode2021\Day2\CommandV1;
use maesierra\AdventOfCode2021\Day2\CommandV2;
use maesierra\AdventOfCode2021\Day2\PasswordPolicy;
use maesierra\AdventOfCode2021\Day2\Position;

const COMMAND_PATTERN = '/^(forward|down|up) (\d+)$/';
class Day2 {

    /**
     * @param string $inputFile
     * @return CommandV1[]
     */
    protected function readCommandsV1(string $inputFile): array
    {
        return array_reduce(explode("\n", file_get_contents($inputFile)), function (&$result, $line) {
            if (preg_match(COMMAND_PATTERN, $line, $matches)) {
                $result[] = new CommandV1($matches[1], $matches[2]);
            }
            return $result;
        }, []);
    }

    /**
     * @param string $inputFile
     * @return CommandV2[]
     */
    protected function readCommandsV2(string $inputFile): array
    {
        return array_reduce(explode("\n", file_get_contents($inputFile)), function (&$result, $line) {
            if (preg_match(COMMAND_PATTERN, $line, $matches)) {
                $result[] = new CommandV2($matches[1], $matches[2]);
            }
            return $result;
        }, []);
    }

    /**
     * @param Command[] $array
     * @return float|int
     */
    private function applyCommands(array $array)
    {
        $position = array_reduce($array, function ($pos, $command) {
            /** @var $command Command */
            $newPosition = $command->apply($pos);
            echo "$command => $newPosition\n";
            return $newPosition;
        }, new Position());
        return $position->depth * $position->hpos;
    }



    /**
     * It seems like the submarine can take a series of commands like forward 1, down 2, or up 3:
     * - forward X increases the horizontal position by X units.
     * - down X increases the depth by X units.
     * - up X decreases the depth by X units.
     *
     * Read the input file, apply the commands and return the product of multiplying horizontal * depth
     *
     * @param $inputFile string file containing an instruction per line
     * @return int product of multiplying horizontal * depth after applying the given commands
     * @throws \Exception
     */
    public function question1($inputFile) {
        return $this->applyCommands($this->readCommandsV1($inputFile));
    }
    /**
     * It seems like the submarine can take a series of commands like forward 1, down 2, or up 3:
     * - forward X does two things:
     *    It increases your horizontal position by X units.
     *   It increases your depth by your aim multiplied by X.
     * - down X increases aim by X units.
     * - up X decreases the aim by X units.
     *
     * Read the input file, apply the commands and return the product of multiplying horizontal * depth
     *
     * @param $inputFile string file containing an instruction per line
     * @return int product of multiplying horizontal * depth after applying the given commands
     * @throws \Exception
     */
    public function question2($inputFile) {
        return $this->applyCommands($this->readCommandsV2($inputFile));
    }

}