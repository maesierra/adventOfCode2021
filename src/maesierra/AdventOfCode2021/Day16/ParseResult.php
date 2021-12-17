<?php


namespace maesierra\AdventOfCode2021\Day16;


class ParseResult {

    /** @var Packet */
    public $packet;
    /** @var int */
    public $pos;

    /**
     * ParseResult constructor.
     * @param Packet $packet
     * @param int $pos
     */
    public function __construct(Packet $packet, int $pos) {
        $this->packet = $packet;
        $this->pos = $pos;
    }


}