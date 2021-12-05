<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day5\Seat;
use PHPUnit\Framework\TestCase;

class Day5Test extends TestCase {

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day5_1.txt";
        $this->assertEquals(5, (new Day5())->question1($inputFile, true));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day5_1.txt";
        $this->assertEquals(12, (new Day5())->question2($inputFile, true));
    }
}