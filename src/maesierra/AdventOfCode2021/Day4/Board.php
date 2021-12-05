<?php

namespace maesierra\AdventOfCode2021\Day4;

class Board {
    const N_COLUMNS = 5;
    const N_ROWS = 5;



    /** @var boolean[][] */
    public $marked;

    public $completed = false;

    /** @var array  */
    private $numbers = [];

    /**
     * Board constructor.
     */
    public function __construct($numbers) {
        $this->marked = array_fill(0, self::N_ROWS, array_fill(0, self::N_COLUMNS, false));
        foreach ($numbers as $r => $row ) {
            foreach ($row as $c => $number) {
                $this->numbers[$number] = [$r, $c];
            }
        }
    }

    public function isCompleted():bool {
        return $this->completed;
    }

    public function playNumber(string $number) {
        $position = $this->numbers[$number] ?? [];
        if ($position) {
            list($row, $col) = $position;
            $this->marked[$row][$col] = true;
            unset($this->numbers[$number]);
            //Check row
            if (array_sum($this->marked[$row]) == self::N_COLUMNS) {
                $this->completed = true;
            }
            //Check column
            if (array_sum(array_column($this->marked, $col)) == self::N_ROWS) {
                $this->completed = true;
            }
        }

    }

    /**
     * @return int[]
     */
    public function unmarked():array{
        return array_keys($this->numbers);
    }

}