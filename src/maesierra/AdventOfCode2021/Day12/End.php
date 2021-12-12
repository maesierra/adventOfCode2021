<?php


namespace maesierra\AdventOfCode2021\Day12;


class End extends  Cave {

    const END = "end";

    /**
     * Cave constructor.
     */
    public function __construct() {
        parent::__construct(self::END);
    }
}