<?php


namespace maesierra\AdventOfCode2021\Day16;


class Sum extends OperatorPacket {

    const TYPE_SUM = 0;

    public function __construct() {
        parent::__construct(self::TYPE_SUM);
    }

    public function complete() {
        $this->value = array_reduce(
            $this->children,
            function ($sum, $p) {
                /** @var Packet $p */
                return $sum + $p->value;
            },
            0
        );
        parent::complete();
    }
}