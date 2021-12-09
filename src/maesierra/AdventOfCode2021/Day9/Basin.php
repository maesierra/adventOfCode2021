<?php


namespace maesierra\AdventOfCode2021\Day9;


use maesierra\AdventOfCode2021\Day9;

class Basin {

    /** @var Point */
    public $lowPoint;
    /** @var Point[] */
    public $points;

    /**
     * Calculates the basin for the given low point.
     * @param $lowPoint Point
     * @param $map
     */
    public function __construct($lowPoint, $map) {
        $this->lowPoint = $lowPoint;
        $this->points[$this->lowPoint->code()] = $this->lowPoint;
        $pointsCache = $this->points;
        $this->expandPoint($this->lowPoint, $map, $pointsCache);
        $points = $this->lowPoint->neighbours();
        while ($points) {
            $next = [];
            foreach ($points as $point) {
                $this->expandPoint($point, $map, $pointsCache);
                $next = array_merge($next, array_filter($point->neighbours(), function($p) {
                    return !isset($this->points[$p->code()]);
                }));
                $this->points[$point->code()] = $point;
            }
            $points = $next;
        }
        $str = "Basin created at $lowPoint (size ".$this->size().")\n" ;
        echo $str;

    }

    /**
     * @param $point Point
     * @param $map
     * @param $pointsCache
     * @return Point
     */
    private function expandPoint($point, $map, &$pointsCache): Point {
        $x = $point->x;
        $y = $point->y;
        //Top
        $row = $y - 1;
        $current = $point;
        while ($row >= 0 && !$current->top) {
            if ($map[$row][$x] == 9) {
                $current->top = new End();
                break;
            } else {
                $p = $this->createPoint($x, $row, $map, ['bottom' => $current], $pointsCache);
                $current->top = $p;
                $current = $p;
            }
            $row--;
        }
        if (!$current->top) {
            $current->top = new End();
        }
        //Bottom
        $row = $y + 1;
        $current = $point;
        while ($row < count($map) && !$current->bottom) {
            if ($map[$row][$x] == 9) {
                $current->bottom = new End();
                break;
            } else {
                $p = $this->createPoint($x, $row, $map, ['top' => $current], $pointsCache);
                $current->bottom = $p;
                $current = $p;
            }
            $row++;
        }
        if (!$current->bottom) {
            $current->bottom = new End();
        }
        //Left
        $column = $x - 1;
        $current = $point;
        while ($column >= 0 && !$current->left) {
            if ($map[$y][$column] == 9) {
                $current->left = new End();
                break;
            } else {
                $p = $this->createPoint($column, $y, $map, ['right' => $current], $pointsCache);
                $current->left = $p;
                $current = $p;
            }
            $column--;
        }
        if (!$current->left) {
            $current->left = new End();
        }
        //right
        $column = $x + 1;
        $current = $point;
        while ($column < count($map[0]) && !$current->right) {
            if ($map[$y][$column] == 9) {
                $current->right = new End();
                break;
            } else {
                $p = $this->createPoint($column, $y, $map, ['left' => $current], $pointsCache);
                $current->right = $p;
                $current = $p;
            }
            $column++;
        }
        if (!$current->right) {
            $current->right = new End();
        }
        return $point;
    }

    public function size():int {
        return count($this->points);
    }

    /**
     * @param $x
     * @param int $y
     * @param int[][] $map
     * @param array $props
     * @param array $pointsCache
     * @return Point
     */
    private function createPoint($x, int $y, $map, $props, &$pointsCache): Point {
        $p = new Point($x, $y, $map[$y][$x]);
        $p = $pointsCache[$p->code()] ?? $p;
        $pointsCache[$p->code()] = $p;
        foreach ($props as $name => $value) {
            if (!$p->{$name}) {
                $p->{$name} = $value;
            }
        }
        return $p;
    }
}