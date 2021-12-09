<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day9Test extends TestCase {

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day9.txt";
        $this->assertEquals(15, (new Day9())->question1($inputFile));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day9.txt";
        $this->assertEquals(1134, (new Day9())->question2($inputFile));
    }
}