<?php


namespace maesierra\AdventOfCode2021\Day16;


class Product extends OperatorPacket {

    const TYPE_PRODUCT = 1;

    public function __construct() {
        parent::__construct(self::TYPE_PRODUCT);
    }

    public function complete() {
        $this->value = array_reduce(
            $this->children,
            function ($product, $p) {
                /** @var Packet $p */
                return $product * $p->value;
            },
            1
        );
        parent::complete();
    }
}