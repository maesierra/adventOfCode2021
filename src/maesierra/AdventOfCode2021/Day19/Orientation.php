<?php


namespace maesierra\AdventOfCode2021\Day19;


use Closure;

class Orientation {

    private static $all = [];

    /** @var Closure */
    public $xModifier;
    /** @var Closure */
    public $yModifier;
    /** @var Closure */
    public $zModifier;

    /**
     * @param Closure $xModifier
     * @param Closure $yModifier
     * @param Closure $zModifier
     */
    public function __construct($xModifier, $yModifier, $zModifier) {
        $this->xModifier = $xModifier;
        $this->yModifier = $yModifier;
        $this->zModifier = $zModifier;
    }


    public static function all():array {
        if (!self::$all) {
            $x = function($x, $y, $z) {
                return $x;
            };
            $xNeg = function($x, $y, $z) {
                return -$x;
            };
            $y = function($x, $y, $z) {
                return $y;
            };
            $yNeg = function($x, $y, $z) {
                return -$y;
            };
            $z = function($x, $y, $z) {
                return $z;
            };
            $zNeg = function($x, $y, $z) {
                return -$z;
            };
            self::$all = [
                new Orientation(   $x,     $y,     $z),
                new Orientation(   $x,     $y,  $zNeg),
                new Orientation(   $x,  $yNeg,     $z),
                new Orientation(   $x,  $yNeg,  $zNeg),
                new Orientation(   $x,     $z,     $y),
                new Orientation(   $x,     $z,  $yNeg),
                new Orientation(   $x,  $zNeg,     $y),
                new Orientation(   $x,  $zNeg,  $yNeg),
                new Orientation($xNeg,     $y,     $z),
                new Orientation($xNeg,     $y,  $zNeg),
                new Orientation($xNeg,  $yNeg,     $z),
                new Orientation($xNeg,  $yNeg,  $zNeg),
                new Orientation($xNeg,     $z,     $y),
                new Orientation($xNeg,     $z,  $yNeg),
                new Orientation($xNeg,  $zNeg,     $y),
                new Orientation($xNeg,  $zNeg,  $yNeg),
                new Orientation(   $y,     $x,     $z),
                new Orientation(   $y,     $x,  $zNeg),
                new Orientation(   $y,  $xNeg,     $z),
                new Orientation(   $y,  $xNeg,  $zNeg),
                new Orientation(   $y,     $z,     $x),
                new Orientation(   $y,     $z,  $xNeg),
                new Orientation(   $y,  $zNeg,     $x),
                new Orientation(   $y,  $zNeg,  $xNeg),
                new Orientation($yNeg,     $x,     $z),
                new Orientation($yNeg,     $x,  $zNeg),
                new Orientation($yNeg,  $xNeg,     $z),
                new Orientation($yNeg,  $xNeg,  $zNeg),
                new Orientation($yNeg,     $z,     $x),
                new Orientation($yNeg,     $z,  $xNeg),
                new Orientation($yNeg,  $zNeg,     $x),
                new Orientation($yNeg,  $zNeg,  $xNeg),
                new Orientation(   $z,     $y,     $x),
                new Orientation(   $z,     $y,  $xNeg),
                new Orientation(   $z,  $yNeg,     $x),
                new Orientation(   $z,  $yNeg,  $xNeg),
                new Orientation(   $z,     $x,     $y),
                new Orientation(   $z,     $x,  $yNeg),
                new Orientation(   $z,  $xNeg,     $y),
                new Orientation(   $z,  $xNeg,  $yNeg),
                new Orientation($zNeg,     $y,     $x),
                new Orientation($zNeg,     $y,  $xNeg),
                new Orientation($zNeg,  $yNeg,     $x),
                new Orientation($zNeg,  $yNeg,  $xNeg),
                new Orientation($zNeg,     $x,     $y),
                new Orientation($zNeg,     $x,  $yNeg),
                new Orientation($zNeg,  $xNeg,     $y),
                new Orientation($zNeg,  $xNeg,  $yNeg),
            ];

        }
        return self::$all;
    }

    public function apply($x, $y, $z, $offsetX = 0, $offsetY = 0, $offsetZ = 0) {
        $xModifier = $this->xModifier;
        $yModifier = $this->yModifier;
        $zModifier = $this->zModifier;
        return [
            $xModifier($x, $y, $z) + $offsetX,
            $yModifier($x, $y, $z) + $offsetY,
            $zModifier($x, $y, $z) + $offsetZ,
        ];
    }
}