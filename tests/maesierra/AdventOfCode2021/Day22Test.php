<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day22\Cuboid;
use PHPUnit\Framework\TestCase;

class Day22Test extends TestCase {

    public function testSubtract() {

        $c1 = new Cuboid(true, -22, -29, -38, 28, 23, 16);
        $c2 = new Cuboid(true, -46,-6,-50,7,46,-1);
        $this->assertEquals(
            [
                new Cuboid(true, -22,-29,0, 28,23,16),
                new Cuboid(true, -22,-29,-38, 28,-7,-1),
                new Cuboid(true, 8,-6,-38, 28,23,-1),
            ],
            $c1->substract($c2)
        );

        $c1 = new Cuboid(true, 2,-3,-24,7,46,-1);
        $c2 = new Cuboid(true, 2,-22,-23,47,22,27);
        $this->assertEquals(
            [
                new Cuboid(true, 2,-3,-24,7,46,-24),
                new Cuboid(true, 2,23,-23,7,46,-1),
            ],
            $c1->substract($c2)
        );

    }

    public function testQuestion1() {

        $day22 = new Day22();

        $this->assertEquals(590784, $day22->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day22.txt"));
        $this->assertEquals(474140, $day22->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day22_2.txt"));
    }

    public function testQuestion2() {
        $day22 = new Day22();
        $this->assertEquals(2758514936282235, $day22->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day22_2.txt"));
    }

}