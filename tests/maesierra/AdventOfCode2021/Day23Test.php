<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day23Test extends TestCase {



//
//    public function testReverse() {
//        $solution = 182;
//        $distances = [2, 3, 4, 5, 6, 7, 8, 9, 10];
//        $costs = [1 => [2, 5, 6], 10 => [2, 5, 6]];
//        while (true) {
//            foreach (array_keys($costs) as $c) {
//
//            }
//        }
//
//        $this->assertEquals(0, 0);
//    }

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day23.txt";
        $this->assertEquals(12521, (new Day23())->question1($inputFile));
    }


    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day23.txt";
        $this->assertEquals("149245887792", (new Day23())->question2($inputFile));
    }
}