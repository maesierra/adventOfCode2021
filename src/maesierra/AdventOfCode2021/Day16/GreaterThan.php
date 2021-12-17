<?php


namespace maesierra\AdventOfCode2021\Day16;


class GreaterThan extends OperatorPacket {

    const TYPE_GREATER_THAN = 5;

    public function __construct() {
        parent::__construct(self::TYPE_GREATER_THAN);
    }

    public function complete() {
        parent::complete();
        $this->value = $this->children[0]->value > $this->children[1]->value ? 1 : 0;
    }
}