<?php


namespace maesierra\AdventOfCode2021\Day22;


class Cuboid {

    /** @var bool */
    public $state;
    /** @var int */
    public $fromX;
    /** @var int */
    public $fromY;
    /** @var int */
    public $fromZ;
    /** @var int */
    public $toX;
    /** @var int */
    public $toY;
    /** @var int */
    public $toZ;

    /** @var int */
    public $volume;

    /** @var int */
    public $nActive;

    /**
     * Cuboid constructor.
     * @param bool $state
     * @param int $fromX
     * @param int $fromY
     * @param int $fromZ
     * @param int $toX
     * @param int $toY
     * @param int $toZ
     */
    public function __construct(bool $state, $fromX, $fromY, $fromZ, $toX, $toY, $toZ) {
        $this->state = $state;
        $this->fromX = min($fromX, $toX);
        $this->fromY = min($fromY, $toY);
        $this->fromZ = min($fromZ, $toZ);
        $this->toX = max($toX, $fromX);
        $this->toY = max($toY, $fromY);
        $this->toZ = max($toZ, $fromZ);
        $this->volume = (
            (abs($this->toX - $this->fromX) + 1) *
            (abs($this->toY - $this->fromY) + 1) *
            (abs($this->toZ - $this->fromZ) + 1)
        );
        $this->nActive = $this->state ? $this->volume : 0;
    }

    /**
     * @param Cuboid $other
     * @return Cuboid[]
     */
    public function substract(Cuboid $other):array {
        echo "************************ {$this} - {$other} = ";
        $intersect = $this->intersect($other);
        if (!$intersect) {
            echo "dont intersect\n";
            return [$this];
        }
        //We'll create up 6 cuboids
        $res = [];
        if ($this->fromZ <= $intersect->fromZ - 1) {
            $res[] = new Cuboid($this->state, $this->fromX, $this->fromY, $this->fromZ, $this->toX, $this->toY, $intersect->fromZ- 1);
        }
        if ($this->toZ >= $intersect->toZ + 1) {
            $res[] = new Cuboid($this->state, $this->fromX, $this->fromY, $intersect->toZ + 1, $this->toX, $this->toY, $this->toZ);
        }
        if ($this->fromY <= $intersect->fromY - 1) {
            $res[] = new Cuboid($this->state, $this->fromX, $this->fromY, $intersect->fromZ, $this->toX, $intersect->fromY - 1, $intersect->toZ);
        }
        if ($this->toY >= $intersect->toY + 1) {
            $res[] = new Cuboid($this->state, $this->fromX, $intersect->toY + 1, $intersect->fromZ, $this->toX, $this->toY, $intersect->toZ);
        }
        if ($this->fromX <= $intersect->fromX - 1) {
            $res[] = new Cuboid($this->state, $this->fromX, $intersect->fromY, $intersect->fromZ, $intersect->fromX - 1, $intersect->toY, $intersect->toZ);
        }
        if ($this->toX >= $intersect->toX + 1) {
            $res[] = new Cuboid($this->state, $intersect->toX + 1, $intersect->fromY, $intersect->fromZ, $this->toX, $intersect->toY, $intersect->toZ);
        }

        echo self::implode($res)."\n";
        return $res;

    }

    /**
     * @param $other Cuboid
     * @return Cuboid|null
     */
    public function intersect(Cuboid $other):?Cuboid {

        if (($this->fromX > $other->toX) ||
            ($this->toX < $other->fromX) ||
            ($this->fromY > $other->toY) ||
            ($this->toY < $other->fromY) ||
            ($this->fromZ > $other->toZ) ||
            ($this->toZ < $other->fromZ)) {
            return null;
        }
        $fromX = max($this->fromX, $other->fromX);
        $fromY = max($this->fromY, $other->fromY);
        $fromZ = max($this->fromZ, $other->fromZ);
        $toX =   min($this->toX,   $other->toX);
        $toY =   min($this->toY,   $other->toY);
        $toZ =   min($this->toZ,   $other->toZ);
        return new Cuboid($this->state, $fromX, $fromY, $fromZ, $toX, $toY, $toZ);
    }


    public function __toString() {
        return "(<{$this->fromX},{$this->fromY},{$this->fromZ}>,<{$this->toX},{$this->toY},{$this->toZ}>)";
        //return ($this->state ? "on":"off")." x={$this->fromX}..{$this->toX},y={$this->fromY}..{$this->toY},z={$this->fromZ}..{$this->toZ}";
    }


    public static function implode($cuboids, $glue = " ") {
        return implode($glue, array_map(function ($c) {
            return "$c";
        }, $cuboids));
    }
}