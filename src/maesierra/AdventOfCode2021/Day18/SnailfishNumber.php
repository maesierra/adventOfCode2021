<?php


namespace maesierra\AdventOfCode2021\Day18;


class SnailfishNumber {

    /** @var SnailfishNumber */
    public $left;
    /** @var SnailfishNumber */
    public $right;

    public $value;

    /** @var SnailfishNumber */
    public $parent;

    /**
     * SnailfishNumber constructor.
     * @param SnailfishNumber|int $left
     * @param SnailfishNumber|int $right
     */
    public function __construct($left, $right = null) {
        if (is_numeric($left) && is_null($right)) {
            $this->value = (int)$left;
        } else {
            $this->left = is_numeric($left) ? new SnailfishNumber($left) : $left;
            $this->left->parent = $this;
            $this->right = is_numeric($right) ? new SnailfishNumber($right) : $right;
            $this->right->parent = $this;
        }
    }

    /**
     * @param $input mixed
     * @return SnailfishNumber
     */
    public static function parse($input) {
        if (is_string($input)) {
            $input = json_decode($input);
        } else if (is_numeric($input)) {
            return new SnailfishNumber($input);
        }
        return new SnailfishNumber(SnailfishNumber::parse($input[0]), SnailfishNumber::parse($input[1]));

    }

    public function add(SnailfishNumber $other) {
        $result = new SnailfishNumber($this, $other);
        do {
            $changed = $result->explode();
            if (!$changed) {
                $changed = $result->split();
            }
        } while ($changed);
        echo "result $result\n";
        return $result;
    }

    /**
     * Explodes the number (if required) or any of the pair components
     * @return bool if there was an explosion
     */
    public function explode():bool {
        if ($this->isLiteral()) {
            return false; //A literal cannot explode
        }
        if ($this->isExploded()) {
            $onLeft = $this->firstLiteralOnLeft();
            if ($onLeft) {
                $onLeft->value += $this->left->value;
            }
            $onRight = $this->firstLiteralOnRight();
            if ($onRight) {
                $onRight->value += $this->right->value;
            }
            //Replace with a 0 literal
            $replacement = new SnailfishNumber(0);
            $replacement->parent = $this->parent;
            $this->parent->{$this->isLeft() ? "left":"right"} = $replacement;
            return true;
        } else {
            return $this->left->explode() || $this->right->explode();
        }
    }

    /**
     * Splits the number (if required) or any of the pair components
     * @return bool true if there was at least one split
     */
    public function split():bool {
        if ($this->isLiteral()) {
            if ($this->value >= 10) {
                $left = (int) ($this->value / 2);
                $replacement = new SnailfishNumber($left, $this->value - $left);
                $replacement->parent = $this->parent;
                $this->parent->{$this->isLeft() ? "left":"right"} = $replacement;
                return true;
            } else {
                return false;
            }
        } else  {
            return $this->left->split() || $this->right->split();
        }
    }

    private function firstLiteralOnLeft() {
        $number = $this;
        while ($number->parent) {
            if (!$number->isLeft()) {
                $number = $number->parent->left;
                while (!$number->isLiteral()) {
                    $number = $number->right;
                }
                return $number;
            }
            $number = $number->parent;
        }
        return null;
    }

    private function firstLiteralOnRight() {
        $number = $this;
        while ($number->parent) {
            if (!$number->isRight()) {
                $number = $number->parent->right;
                while (!$number->isLiteral()) {
                    $number = $number->left;
                }
                return $number;
            }
            $number = $number->parent;
        }
        return null;
    }

    /**
     * @return bool true if this number is the left number on its parent
     */
    public function isLeft():bool {
        if (!$this->parent) {
            return false;
        } else {
            return $this === $this->parent->left;
        }
    }

    /**
     * @return bool true if this number is the right number on its parent
     */
    public function isRight():bool {
        if (!$this->parent) {
            return false;
        } else {
            return $this === $this->parent->right;
        }
    }

    public function __toString() {
        if ($this->isLiteral()) {
            return "{$this->value}";
        } else {
            return "[{$this->left},{$this->right}]";
        }
    }

    /**
     * @return bool true if the number is a literal and not a pair
     */
    private function isLiteral(): bool {
        return !is_null($this->value);
    }

    private function nestedLevel() {
        $count = 0;
        $parent = $this->parent;
        while ($parent && $count < 4) {
            $count++;
            $parent = $parent->parent;
        }
        return $count;
    }

    private function isExploded() {
        if ($this->isLiteral()) {
            return false;
        }
        return $this->nestedLevel() >= 4;
    }



    public function magnitude() {
        if ($this->isLiteral()) {
            return $this->value;
        } else {
            return (3 * $this->left->magnitude()) + (2 * $this->right->magnitude());
        }

    }

}