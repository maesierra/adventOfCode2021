<?php


namespace maesierra\AdventOfCode2021\Day9;


class Point {


    public $x;
    public $y;
    public $height;
    /** @var Point */
    public $top;
    /** @var Point */
    public $bottom;
    /** @var Point */
    public $left;
    /** @var Point */
    public $right;

    /**
     * Point constructor.
     * @param $x
     * @param $y
     * @param $height
     */
    public function __construct($x, $y, $height) {
        $this->x = $x;
        $this->y = $y;
        $this->height = $height;
    }

    /**
     * @return Point[]
     */
    public function neighbours():array {
        return array_reduce([$this->top, $this->bottom, $this->left, $this->right], function(&$res, $p) {
            if ($p and !($p instanceof End)) {
                $res[] = $p;
            }
            return $res;
        }, []);
    }

    public function code():string {
        return "{$this->x}:{$this->y}";
    }

    public function __toString() {
        return $this->code()." => ".$this->height;
    }


}