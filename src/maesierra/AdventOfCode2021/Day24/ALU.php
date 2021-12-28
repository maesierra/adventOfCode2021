<?php


namespace maesierra\AdventOfCode2021\Day24;



class ALU {

    /** @var int */
    public $w = 0;
    /** @var int */
    public $x = 0;
    /** @var int */
    public $y = 0;
    /** @var int */
    public $z = 0;


    /**
     * @param $instructions
     * @return Instruction[]
     */
    private function load($instructions) {
        $instructions = array_reduce($instructions, function(&$result, $line) {
            $line = trim($line);
            if ($line) {
                if (preg_match('/^(add|mul|div|mod|eql) (.*) (.*)$/', $line, $matches)) {
                    $result[] = new Instruction($matches[1], $matches[2], $matches[3]);
                } else if (preg_match('/^(inp) (.*)$/', $line, $matches)) {
                    $result[] = new Instruction($matches[1], $matches[2], null);
                }
                return $result;
            }
            return $result;
        }, []);
        return $instructions;

    }

    /**
     * @param $instructions string[]
     * @param $input int[]
     */
    public function run($instructions, $input) {
        $instructions = $this->load($instructions);
        foreach ($instructions as $i) {
            if ($i->type == 'inp') {
                $this->{$i->operand1} = array_shift($input);
                continue;
            }
            $value1 = $this->{$i->operand1};
            $value2 = is_numeric($i->operand2) ? (int) $i->operand2 : $this->{$i->operand2};
            $res = null;
            switch ($i->type) {
                case 'add':
                    $res = $value1 + $value2;
                    break;
                case 'mul':
                    $res = $value1 * $value2;
                    break;
                case 'div':
                    $res = (int) ($value1 / $value2);
                    break;
                case 'mod':
                    $res = $value1 % $value2;
                    break;
                case 'eql':
                    $res = (int)($value1 == $value2);
                    break;
            }
            $this->{$i->operand1} = $res;
        }
    }

    public function reset() {
        $this->w = 0;
        $this->x = 0;
        $this->y = 0;
        $this->z = 0;
    }

    public function getState():array {
        return (array) $this;
    }
    public function loadState($state) {
        foreach ($state as $variable => $value) {
            $this->{$variable} = $value;
        }
    }

}