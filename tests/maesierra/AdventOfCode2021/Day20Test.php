<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day20\Connection;
use PHPUnit\Framework\TestCase;

class Day20Test extends TestCase {

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day20.txt";
        $this->assertEquals(35, (new Day20())->question1($inputFile));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day20.txt";
        $this->assertEquals(3351, (new Day20())->question2($inputFile));
    }

}