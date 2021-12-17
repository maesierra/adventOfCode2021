<?php


namespace maesierra\AdventOfCode2021\Day16;


class LessThan extends OperatorPacket {

    const TYPE_LESS_THAN = 6;

    public function __construct() {
        parent::__construct(self::TYPE_LESS_THAN);
    }

    public function complete() {
        parent::complete();
        $this->value = $this->children[0]->value < $this->children[1]->value ? 1 : 0;
    }
}