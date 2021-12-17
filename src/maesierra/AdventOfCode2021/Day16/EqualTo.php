<?php


namespace maesierra\AdventOfCode2021\Day16;


class EqualTo extends OperatorPacket {

    const TYPE_EQUAL_TO = 7;

    public function __construct() {
        parent::__construct(self::TYPE_EQUAL_TO);
    }

    public function complete() {
        parent::complete();
        $this->value = $this->children[0]->value == $this->children[1]->value ? 1 : 0;
    }
}