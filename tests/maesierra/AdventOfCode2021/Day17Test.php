<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day17Test extends TestCase {

    public function testQuestion1() {
        $this->assertEquals(45, (new Day17())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day17.txt", 30, 100));
    }

    public function testQuestion2() {
        $this->assertEquals(112, (new Day17())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day17.txt", 30, 100));
    }

}