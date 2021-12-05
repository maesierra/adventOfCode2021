<?php


namespace maesierra\AdventOfCode2021\Day5;


class Line {

    const LINE_VERTICAL = 1;
    const LINE_HORIZONTAL = 2;
    const LINE_DIAGONAL = 3;

    public $x1;
    public $y1;
    public $x2;
    public $y2;

    /**
     * Line constructor.
     * @param $x1
     * @param $y1
     * @param $x2
     * @param $y2
     */
    public function __construct($x1, $y1, $x2, $y2)
    {
        $this->x1 = (int) $x1;
        $this->y1 = (int) $y1;
        $this->x2 = (int) $x2;
        $this->y2 = (int) $y2;

    }

    /**
     * @return false|int
     */
    public function lineType() {
        if ($this->x1 == $this->x2) {
            return self::LINE_VERTICAL;
        } else if ($this->y1 == $this->y2) {
            return self::LINE_HORIZONTAL;
        }
        return self::LINE_DIAGONAL;
    }

    /**
     * Applies the line to the given map, increasing the points it touches
     * @param $map int[][]
     * @return int[][]
     */
    public function apply(array $map):array {
        $res = $map;
        //Expand the map to make sure the line will fit
        if (!isset($res[$this->y1])) {
            $res = $this->expandRows($res, 0, $this->y1);
        }
        if (!isset($res[$this->y2])) {
            $res = $this->expandRows($res, count($res), $this->y2);
        }
        if (!isset($res[$this->y1][$this->x1])) {
            $res = $this->expandCols($res, $this->x1);
        }
        if (!isset($res[$this->y2][$this->x2])) {
            $res = $this->expandCols($res, $this->x2);
        }
        switch ($this->lineType()) {
            case self::LINE_VERTICAL:
                return $this->drawVertical($res);
            case self::LINE_HORIZONTAL;
                return $this->drawHorizontal($res);
            case self::LINE_DIAGONAL:
                return $this->drawDiagonal($res);
        }
        return $res;
    }

    private function drawVertical(array $map):array {
        $slopeY = $this->y2 > $this->y1 ? 1 : -1;
        for ($i = $this->y1; $i != $this->y2; $i+=$slopeY) {
            $map[$i][$this->x1] += 1;
        }
        $map[$i][$this->x1] += 1;
        return $map;
    }

    private function drawHorizontal(array $map):array {
        $slopeX = $this->x2 > $this->x1 ? 1 : -1;
        for ($i = $this->x1; $i != $this->x2; $i+=$slopeX) {
            $map[$this->y1][$i] += 1;
        }
        $map[$this->y1][$i] += 1;
        return $map;
    }

    private function drawDiagonal(array $map):array {
        $slopeY = $this->y2 > $this->y1 ? 1 : -1;
        $slopeX = $this->x2 > $this->x1 ? 1 : -1;
        for ($i = $this->y1, $j= $this->x1; $j != $this->x2 && $i != $this->y2; $i += $slopeY, $j += $slopeX) {
            $map[$i][$j] += 1;
        }
        $map[$i][$j] += 1;
        return $map;
    }

    /**
     * @param $map int[][]
     * @param $from int
     * @param $to int
     * @return array
     */
    private function expandRows(array $map, int $from , int $to): array {
        $nCols = count($map ?? []) ?: 1;
        for ($i = $from; $i <= $to; $i++) {
            $map[] = array_fill(0, $nCols, 0);
        }
        return $map;
    }

    private function expandCols(array $map, int $to):array {
        $nRows = count($map) ?: 1;
        $nCols = count($map[0]);
        for ($i = $nCols; $i <= $to; $i++) {
            for ($j = 0; $j < $nRows; $j++) {
                $map[$j][$i] = 0;
            }
        }
        return $map;
    }

    public function __toString() {
        return "({$this->x1},{$this->y1}) -> ({$this->x2},{$this->y2})";
    }

}