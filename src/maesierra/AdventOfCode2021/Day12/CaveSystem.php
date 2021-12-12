<?php


namespace maesierra\AdventOfCode2021\Day12;


use Closure;

class CaveSystem {

    /** @var Cave[] */
    public $caves;
    public $start;
    /** @var SmallCave[] */
    public $smallCaves;


    /**
     * CaveSystem constructor.
     * @param Closure
     * @param Cave[] $caves
     */
    public function __construct(array $caves) {
        $this->caves = $caves;
        $this->smallCaves = array_filter($caves, function($c) {
           return $c instanceof SmallCave && !$c instanceof Start;
        });
        $this->start = $this->caves[Start::START];
    }

    /**
     * @param null $path
     * @return Path[]
     */
    public function allPaths($path = null) {
        if (!$path) {
            $path = new Path($this->start);
        }
        $start = $path->end();
        $found = [];
        foreach ($this->getCandidates($start, $path) as $c) {
            $newPath = new Path($c, $path);
            if ($c instanceof End) {
                $found[] = $newPath;
                echo "$newPath\n";
            } else {
                $found = array_merge($found, $this->allPaths($newPath));
            }
        }
        return $found;
    }

    /**
     * @param null $path
     * @return Path[]
     */
    public function allPathsAllowingTwice($path = null) {
        if (!$path) {
            $path = new Path($this->start);
        }
        $start = $path->end();
        $found = [];
        foreach ($this->getCandidates($start, $path) as $c) {
            $newPath = new Path($c, $path);
            if ($c instanceof End) {
                $found[] = $newPath;
            } else {
                if ($c instanceof SmallCave && !$path->allowed) {
                    $found = array_merge($found, $this->allPathsAllowingTwice(new Path($c, $path, $c)));
                }
                $found = array_merge($found, $this->allPathsAllowingTwice($newPath));
            }
        }
        return $found;
    }

    /**
     * @param Cave $start
     * @param Path $path
     * @return Cave[]
     */
    private function getCandidates(Cave $start, Path $path): array
    {
        /** @var Cave[] $candidates */
        $candidates = array_reduce($start->connections, function (&$res, $c) use ($start, $path) {
            /** @var $c Cave */
            if ($c instanceof SmallCave && $path->alreadyVisited($c)) {
                return $res;
            }
            $res[] = $c;
            return $res;
        }, []);
        return $candidates;
    }

}