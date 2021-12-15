<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day14Test extends TestCase {

    public function testQuestion1() {
        $this->assertEquals(1588, (new Day14())->question1(__DIR__ . DIRECTORY_SEPARATOR . "Day14.txt"));
    }

    public function testQuestion2() {
        $t = array_fill(0, 4980737, "a");
        $s = "";
        foreach ($t as $a) {
            $s.= $a;
        }
        echo strlen($s);
        $this->assertEquals(2188189693529, (new Day14())->question2(__DIR__ . DIRECTORY_SEPARATOR . "Day14.txt"));
    }
}