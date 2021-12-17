<?php


namespace maesierra\AdventOfCode2021\Day16;


class Minimum extends OperatorPacket {

    const TYPE_MINIMUM = 2;

    public function __construct() {
        parent::__construct(self::TYPE_MINIMUM);
    }

    public function complete() {
        $this->value = array_reduce(
            $this->children,
            function ($min, $p) {
                /** @var Packet $p */
                return min($min, $p->value);
            },
            PHP_INT_MAX
        );
        parent::complete();
    }
}