<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:43
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day16\Packet;
use PHPUnit\Framework\TestCase;

class Day16Test extends TestCase {

    public function versionSum()
    {
        return [
            ["100010100000000001001010100000000001101010000000000000101111010001111000", 16],
            ["01100010000000001000000000000000000101100001000101010110001011001000100000000010000100011000111000110100", 12],
            ["110100101111111000101000", 6],
            ["00111000000000000110111101000101001010010001001000000000", 9],
            ["11101110000000001101010000001100100000100011000001100000", 14],
            ["1100000000000001010100000000000000000001011000010001010110100010111000001000000000101111000110000010001101000000", 23],
            ["101000000000000101101100100010000000000101100010000000010111110000110110100001101011000110001010001111010100011110000000", 31]
        ];
    }

    /**
     * @dataProvider versionSum
     *
     */
    public function testVersionSum($stream, $versionSum) {
        $day16 = new Day16();
        $packets = $day16->parsePacket(str_split($stream))->packet;
        $this->assertEquals($versionSum, $packets->versionSum());
    }

    public function value()
    {
        return [
            ["1100001000000000101101000000101010000010", 3],
            ["000001000000000001011010110000110011100010010000", 54],
            ["10001000000000001000011011000011111010001000000100010010", 7],
            ["11001110000000001100010000111101100010000001000100100000", 9],
            ["110110000000000001011010110000101010100011110000", 1],
            ["1111011000000000101111000010110110001111", 0],
            ["100111000000000001011010110000101111100011110000", 0],
            ["10011100000000010100000100001000000000100101000000110010000011110001100000000010000100000100101000001000", 1],
        ];
    }

    /**
     * @dataProvider value
     *
     */
    public function testAppliedValues($stream, $expected) {
        $day16 = new Day16();
        $packet = $day16->parsePacket(str_split($stream))->packet;
        $this->assertEquals($expected, $packet->value);
    }

    public function testQuestion1() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day16.txt";
        $this->assertEquals(14, (new Day16())->question1($inputFile));
    }

    public function testQuestion2() {
        $inputFile = __DIR__ . DIRECTORY_SEPARATOR . "Day16_2.txt";
        $this->assertEquals(54, (new Day16())->question2($inputFile));
    }
}