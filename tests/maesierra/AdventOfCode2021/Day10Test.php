<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day10Test extends TestCase {


    public function testQuestion1() {
        $this->assertEquals(26397, (new Day10())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day10_1.txt"));
    }

    public function testQuestion2() {
        $this->assertEquals(288957, (new Day10())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day10_1.txt"));
    }
}