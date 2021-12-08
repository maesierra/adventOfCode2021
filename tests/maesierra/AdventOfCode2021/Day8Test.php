<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day8\Reading;
use PHPUnit\Framework\TestCase;

class Day8Test extends TestCase {

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day8.txt";
        $this->assertEquals(26, (new Day8())->question1($inputFile));
    }

    public function testTranslate() {
        $reading = new Reading(['acedgfb', 'cdfbe', 'gcdfa', 'fbcad', 'dab', 'cefabd', 'cdfgeb', 'eafb', 'cagedb', 'ab'], ['cdfeb', 'fcadb', 'cdfeb', 'cdbaf']);
        $this->assertEquals(5353, $reading->translate());

    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day8.txt";
        $this->assertEquals(61229, (new Day8())->question2($inputFile));
    }
}