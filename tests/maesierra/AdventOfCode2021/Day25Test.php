<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use PHPUnit\Framework\TestCase;

class Day25Test extends TestCase {

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day25.txt";
        $this->assertEquals(58, (new Day25())->question1($inputFile));
    }

}