<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day15Test extends TestCase {

    public function testQuestion1() {
        $this->assertEquals(40, (new Day15())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day15.txt"));
    }

    public function testQuestion2() {
        $this->assertEquals(315, (new Day15())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day15.txt"));
    }
}