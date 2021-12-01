<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day1Test extends TestCase {

    public function testReport1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day1.txt";
        $this->assertEquals(7, (new Day1())->question1($inputFile));
    }

    public function testReport2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day1.txt";
        $this->assertEquals(5, (new Day1())->question2($inputFile));
    }
}