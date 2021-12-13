<?php


namespace maesierra\AdventOfCode2021\Day13;


class FoldInstruction {
    public $axis;
    public $line;

    /**
     * FoldInstruction constructor.
     * @param $axis
     * @param $line
     */
    public function __construct($axis, $line) {
        $this->axis = $axis;
        $this->line = $line;
    }

    public function fold($grid): array {
        $res = [];
        if ($this->axis == "y") {
            for ($i = 0; $i< $this->line; $i++) {
               $res[$i] = $grid[$i];
            }
            $nRows = count($grid);
            for ($i = $this->line + 1; $i< $nRows; $i++) {
                $dest = $this->line - ($i - $this->line);
                foreach ($grid[$i] as $j => $v) {
                    $res[$dest][$j] = $res[$dest][$j] || $v;
                }
            }
        } else {
           foreach ($grid as $i => $row) {
               for ($j = 0; $j< $this->line; $j++) {
                   $res[$i][$j] = $grid[$i][$j];
               }
               $nCols = count($grid[0]);
               for ($j = $this->line + 1; $j< $nCols; $j++) {
                   $dest = $this->line - ($j - $this->line);
                   $res[$i][$dest] = $res[$i][$dest] || $grid[$i][$j];
               }
           }
        }
        return $res;
    }


}