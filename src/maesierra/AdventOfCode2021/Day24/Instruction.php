<?php


namespace maesierra\AdventOfCode2021\Day24;


class Instruction {
    /** @var string */
    public $type;
    /** @var string */
    public $operand1;
    /** @var string|int */
    public $operand2;

    /**
     * Instruction constructor.
     * @param string $type
     * @param int|string $operand1
     * @param int|string $operand2
     */
    public function __construct(string $type, $operand1, $operand2)
    {
        $this->type = $type;
        $this->operand1 = $operand1;
        $this->operand2 = $operand2;
    }



}