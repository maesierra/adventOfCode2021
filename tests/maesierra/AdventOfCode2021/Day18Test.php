<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day18\SnailfishNumber;
use PHPUnit\Framework\TestCase;

class Day18Test extends TestCase {

    public function testAddition() {
        $n1 = new SnailfishNumber(
            new SnailfishNumber(new SnailfishNumber(new SnailfishNumber(4, 3), new SnailfishNumber(4)), new SnailfishNumber(4)),
            new SnailfishNumber(new SnailfishNumber(7), new SnailfishNumber(new SnailfishNumber(8, 4), new SnailfishNumber(9)))
        );
        $n2 = new SnailfishNumber(1, 1);

        $expected = new SnailfishNumber(
            new SnailfishNumber(
                new SnailfishNumber(new SnailfishNumber(0, 7), new SnailfishNumber(4)),
                new SnailfishNumber(new SnailfishNumber(7, 8), new SnailfishNumber(6, 0))
            ),
            new SnailfishNumber(8, 1)
        );
        $this->assertEquals($expected->__toString(), $n1->add($n2)->__toString());
    }

    public function testParse() {
        $expected = new SnailfishNumber(
            new SnailfishNumber(new SnailfishNumber(new SnailfishNumber(4, 3), new SnailfishNumber(4)), new SnailfishNumber(4)),
            new SnailfishNumber(new SnailfishNumber(7), new SnailfishNumber(new SnailfishNumber(8, 4), new SnailfishNumber(9)))
        );
        $this->assertEquals($expected, SnailfishNumber::parse("[[[[4,3],4],4],[7,[[8,4],9]]]"));
    }

    public function magnitudes() {
        return [
            [[[1,2],[[3,4],5]],                                      143],
            [[[[[0,7],4],[[7,8],[6,0]]],[8,1]],                     1384],
            [[[[[1,1],[2,2]],[3,3]],[4,4]],                          445],
            [[[[[3,0],[5,3]],[4,4]],[5,5]],                          791],
            [[[[[5,0],[7,4]],[5,5]],[6,6]],                         1137],
            [[[[[8,7],[7,7]],[[8,6],[7,7]]],[[[0,7],[6,6]],[8,7]]], 3488],
        ];
    }

    /**
     * @dataProvider magnitudes
     */
    public function testMagnitude($number, $expected) {
        $this->assertEquals($expected, SnailfishNumber::parse($number)->magnitude());
    }

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day18.txt";
        $this->assertEquals(4140, (new Day18())->question1($inputFile));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day18.txt";
        $this->assertEquals(3993, (new Day18())->question2($inputFile));
    }
}