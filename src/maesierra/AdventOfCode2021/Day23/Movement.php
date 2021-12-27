<?php


namespace maesierra\AdventOfCode2021\Day23;


class Movement {

    /** @var string */
    public $amphipod;
    /** @var string */
    public $from;
    /** @var $to string */
    public $to;

    private $cost;

    /**
     * Movement constructor.
     * @param string $amphipod
     * @param string $from
     * @param string $to
     */
    public function __construct($amphipod, $from, $to, $cost)
    {
        $this->amphipod = $amphipod;
        $this->from = $from;
        $this->to = $to;
        $this->cost = $cost;
    }


    public function __toString() {
        return "{$this->amphipod} from {$this->from} to {$this->to}";
    }


    /**
     * @param $movements Movement[]
     * @return int
     */
    public static function totalCost($movements):int {
        if (!$movements) {
            return 0;
        }
        return array_reduce($movements, function ($cost, $m) {
            /** @var Movement $m */
            return $cost + $m->cost;
        }, 0);
    }

}