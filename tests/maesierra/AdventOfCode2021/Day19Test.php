<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day19Test extends TestCase {

    public function testQuestion1() {
        $this->assertEquals(79, (new Day19())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day19.txt"));
    }

    public function testQuestion2() {
        $this->assertEquals(3621, (new Day19())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day19.txt"));
    }
}