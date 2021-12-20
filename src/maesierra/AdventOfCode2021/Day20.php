<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day20\Algorithm;
use maesierra\AdventOfCode2021\Day20\Connection;
use maesierra\AdventOfCode2021\Day20\PlacedTile;
use maesierra\AdventOfCode2021\Day20\Tile;

class Day20 {

    const DARK = '.';
    const LIT = '#';
    const INFINITE = '*';

    /**
     * @var Algorithm
     */
    private $algorithm;

    /**
     * Reads the algorithm and returns the image as binary pixels
     * @param $inputFile
     * @return string[][]
     */
    public function readInput($inputFile):array {
        $lines = explode("\n", file_get_contents($inputFile));
        $this->algorithm = new Algorithm(array_map(function ($c) {
            return $c == self::LIT;
        }, str_split(array_shift($lines))));
        return array_reduce($lines, function (&$result, $line) {
                $line = trim($line);
                if ($line) {
                    $result[] = str_split($line);
                }
                return $result;
            }, []);
    }

    /**
     * @param string[][] $image
     * @param int $x
     * @param int $y
     * @return string values for the 9 adjacent pixels (including the pixel itself), completed with a dark pixel
     */
    private static function adjacentPixels(array $image, int $x, int $y) {
        $adjacent = '';
        for ($posY = $y - 1; $posY <= $y + 1; $posY++) {
            for ($posX = $x - 1; $posX <= $x + 1; $posX ++) {
                $adjacent.= ($image[$posY] ?? [])[$posX] ?? self::INFINITE;
            }
        }
        return $adjacent;
    }

    public static function printImage($image) {
        return implode("\n", array_map(function($row) {
            return implode("", $row);
        }, $image));
    }

    /**
     * @param $image string[][]
     * @param int $steps
     * @return string[][]
     */
    private function enhanceImage($image, $steps = 1) {
        for ($i = 0; $i<$steps; $i++) {
            echo "applying enhance step $i($steps)\n";
            $nRows = count($image);
            $nCols = count($image[0]);
            $enhanced = [];
            for ($y = -1; $y < $nRows + 1; $y++) {
                for ($x = -1; $x < $nCols + 1; $x++) {
                    $adjacent = self::adjacentPixels($image, $x, $y);
                    $enhanced[$y][$x] = $this->algorithm->apply($adjacent);
                }
            }
            //Reindex the image
            $image = array_values(
                array_map(function($row) {
                    return array_values($row);
                }, $enhanced)
            );
            $this->algorithm->nextStep();
        }
        return $image;
    }


    public function question1($inputFile, $steps = 2):int {
        $image = $this->readInput($inputFile);
        $image = $this->enhanceImage($image, $steps);
        echo self::printImage($image)."\n\n\n";
        //Count the lit pixels
        return array_reduce($image, function ($c, $row) {
            return $c + array_count_values($row)[self::LIT] ?? 0;
        }, 0);
    }

    public function question2($inputFile):int {
        return $this->question1($inputFile, 50);
    }

}
