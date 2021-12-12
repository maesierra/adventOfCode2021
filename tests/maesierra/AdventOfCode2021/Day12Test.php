<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day12Test extends TestCase {


    public function testQuestion1() {
        $this->assertEquals(10, (new Day12())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day12.txt"));
        $this->assertEquals(19, (new Day12())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day12_2.txt"));
        $this->assertEquals(226, (new Day12())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day12_3.txt"));
    }

    public function testQuestion2() {
        $this->assertEquals(36, (new Day12())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day12.txt"));
        $this->assertEquals(103, (new Day12())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day12_2.txt"));
        $this->assertEquals(3509, (new Day12())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day12_3.txt"));
    }
}