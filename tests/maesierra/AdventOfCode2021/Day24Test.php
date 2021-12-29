<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day24\ALU;
use PHPUnit\Framework\TestCase;

class Day24Test extends TestCase {




    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day24.txt";
        $this->assertNotNull((new Day24())->question1($inputFile, ['param1' => 11]));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day24.txt";
        $this->assertNotNull((new Day24())->question2($inputFile, [/*'param1' => 11*/]));
    }

    public function testALU(): void
    {
        $alu = new ALU();
        $program1 = [
            "inp x",
            "mul x -1",
        ];
        $alu->run($program1, [10]);
        $this->assertEquals(-10, $alu->x);
        $program2 = [
            "inp z",
            "inp x",
            "mul z 3",
            "eql z x",
        ];
        $alu->run($program2, [3, 9]);
        $this->assertEquals(1, $alu->z);
        $alu->reset();
        $alu->run($program2, [3, 5]);
        $this->assertEquals(0, $alu->z);
    }
}