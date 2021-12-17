<?php


namespace maesierra\AdventOfCode2021\Day16;


class Literal extends Packet {

    const TYPE_LITERAL = 4;

    /**
     * PacketType4 constructor.
     */
    public function __construct() {
        $this->type = self::TYPE_LITERAL;
    }

    public function addLiteralBits(string $bits) {
        $controlBit = substr($bits, 0, 1) ;
        $this->value.= substr($bits, 1);
        if ($controlBit == "0") {
            $this->value = base_convert($this->value, 2, 10);
            $this->complete();
        }
    }
}