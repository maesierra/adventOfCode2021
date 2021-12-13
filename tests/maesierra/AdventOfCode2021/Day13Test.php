<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day13Test extends TestCase {


    public function testQuestion1() {
        $this->assertEquals(17, (new Day13())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day13.txt"));
    }

    public function testQuestion2() {
        $output =
            "#####\n" .
            "#...#\n" .
            "#...#\n" .
            "#...#\n" .
            "#####\n" .
            ".....\n" .
            ".....";
        $this->assertEquals($output, (new Day13())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day13.txt"));
    }
}