<?php


namespace maesierra\AdventOfCode2021\Day12;


class Path {

    /** @var Cave[]  */
    public $caves = [];
    /** @var string[] */
    private $caveNames;
    /**
     * @var Cave|null
     */
    public $allowed;

    /**
     * @param Cave $new
     * @param Path|null $caves
     */
    public function __construct($new, $path = null, $allowed = null) {
        if ($path) {
            $this->caves = $path->caves;
            $this->allowed = $path->allowed;
        }
        $this->caves[] = $new;
        $this->caveNames = array_map(function($c) {
            return $c->name;
        }, $this->caves);
        if ($allowed) {
            $this->allowed = $allowed;
        }

    }


    /**
     * @param $cave Cave
     * @return bool
     */
    public function alreadyVisited(Cave $cave) {
        if ($cave === $this->allowed) {
            return count(array_filter($this->caves, function($c) use($cave) {
                    return $c == $cave;
                })) == 2;
        } else {
            return in_array($cave->name, $this->caveNames);
        }
    }

    public function end():Cave {
        return end($this->caves);
    }

    public function __toString() {
        return implode(",", array_map(function($c) {
            return $c->name;
        }, $this->caves));
    }

}