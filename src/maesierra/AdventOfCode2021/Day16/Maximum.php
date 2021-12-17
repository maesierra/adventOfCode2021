<?php


namespace maesierra\AdventOfCode2021\Day16;


class Maximum extends OperatorPacket {

    const TYPE_MAXIMUM = 3;

    public function __construct() {
        parent::__construct(self::TYPE_MAXIMUM);
    }

    public function complete() {
        $this->value = array_reduce(
            $this->children,
            function ($max, $p) {
                /** @var Packet $p */
                return max($max, $p->value);
            },
            0
        );
        parent::complete();
    }
}