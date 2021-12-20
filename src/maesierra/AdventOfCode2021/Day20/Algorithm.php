<?php


namespace maesierra\AdventOfCode2021\Day20;


use maesierra\AdventOfCode2021\Day20;

class Algorithm {

    /** @var bool[] */
    private $sequence;
    private $infiniteState = Day20::DARK;

    /**
     * Algorithm constructor.
     * @param bool[] $sequence
     */
    public function __construct(array $sequence) {
        $this->sequence = $sequence;
    }

    /**
     * Applies the algorithm to the 9 pixel block
     * @param string $pixels
     * @return string
     */
    public function apply(string $pixels) {
        $pixels = strtr($pixels, Day20::INFINITE, $this->infiniteState);
        $number = base_convert(strtr($pixels, Day20::LIT.Day20::DARK, "10"), 2, 10);
        return $this->sequence[$number] ? Day20::LIT : Day20::DARK;
    }

    public function nextStep() {
        //Change infinite state
        $this->infiniteState = $this->apply(str_repeat($this->infiniteState, 9));
    }

}