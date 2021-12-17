<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;

use Closure;
use maesierra\AdventOfCode2021\Day12\BigCave;
use maesierra\AdventOfCode2021\Day12\Cave;
use maesierra\AdventOfCode2021\Day12\CaveSystem;
use maesierra\AdventOfCode2021\Day12\End;
use maesierra\AdventOfCode2021\Day12\PathAllowingTwice;
use maesierra\AdventOfCode2021\Day12\SmallCave;
use maesierra\AdventOfCode2021\Day12\Start;

class Day12 {


    /**
     * @param $name
     * @return Cave
     */
    private function createCave($name):Cave {
        if ($name == Start::START) {
            return new Start();
        } else if ($name == End::END) {
            return new End();
        } else if (ctype_lower($name)) {
            return new SmallCave($name);
        } else {
            return new BigCave($name);
        }
    }

    /**
     * @param $inputFile string
     * @param $pathFactory Closure
     * @return CaveSystem
     */
    private function parseCaveSystem(string $inputFile): CaveSystem
    {
        $caves = array_reduce(explode("\n", file_get_contents($inputFile)), function (&$result, $line) {
            list($c1, $c2) = explode("-", $line);
            /** @var $c1 Cave */
            /** @var $c2 Cave */
            $c1 = $result[$c1] ?? $this->createCave($c1);
            $c2 = $result[$c2] ?? $this->createCave($c2);
            $result[$c1->name] = $c1;
            $result[$c2->name] = $c2;
            $c1->link($c2);
            $c2->link($c1);
            return $result;
        }, []);
        return new CaveSystem($caves);
    }


    /**
     * https://adventofcode.com/2021/day/12
     * @param $inputFile
     * @return int How many paths through this cave system are there that visit small caves at most once
     */
    public function question1($inputFile): int {
        $caveSystem = $this->parseCaveSystem($inputFile);
        $allPaths = $caveSystem->allPaths();
        return count($allPaths);
    }

    /**
     * https://adventofcode.com/2021/day/12
     * @param $inputFile
     * @return int How many paths through this cave system are there that visit small caves at most once but one that can be visited twice
     */
    public function question2($inputFile): int {
        $caveSystem = $this->parseCaveSystem($inputFile);
        $allPaths = array_unique(array_map(function($p) {
            return $p->__toString();
        }, $caveSystem->allPathsAllowingTwice()));

        echo implode("\n", $allPaths)."\n";
        return count($allPaths);
    }
}