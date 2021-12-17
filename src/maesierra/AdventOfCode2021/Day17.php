<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;




use Closure;

class Day17 {

    private function readInput($inputFile):array {
        preg_match("/^target area: x=(-?\d+)..(-?\d+), y=(-?\d+)..(-?\d+)$/",trim(file_get_contents($inputFile)), $matches);
        return [
          [(int)$matches[1], (int)$matches[2]],
          [(int)$matches[3], (int)$matches[4]]
        ];
    }

    /***
     * @param $x
     * @param $y
     * @param $targetArea
     * @return bool true if x,y are in the target area
     */
    private function hits($x, $y, $targetArea) {
        return $x >= $targetArea[0][0] &&
               $x <= $targetArea[0][1] &&
               $y >= $targetArea[1][0] &&
               $y <= $targetArea[1][1];
    }

    /**
     * @param $x
     * @param $y
     * @param $targetArea
     * @return bool true if x or y are greater than the max target area
     */
    private function missed($x, $y, $targetArea) {
        $maxX = max($targetArea[0][1], $targetArea[0][0]);
        $overshootX = $x > $maxX;
        $maxY = min($targetArea[1][1], $targetArea[1][0]);
        $overshootY = $y < $maxY;
        return $overshootX || $overshootY;
    }
    private function trajectoryHits($speedX, $speedY, $targetArea) {
        $x = 0;
        $y = 0;
        $maxY = 0;
        //Calculate until x is stable
        while (!$this->missed($x, $y, $targetArea)) {
            $prevX = $x ?: -1;
            $x += $speedX;
            $y += $speedY;
            $maxY = max($maxY, $y);
            $speedX = max(0, $speedX - 1);
            $speedY -= 1;
            if ($this->hits($x, $y, $targetArea)) {
                return $maxY;
            }
            else if($prevX == $x && $x < $targetArea[0][0]) {
                return false; //It will never reach
            }
        }
        return false;
    }
    /**
     * @param $inputFile string
     * @param $maxY int max speed y to be attempted
     * @param $maxX int  max speed x to be attempted
     * @param $onHit Closure action taking the maxHeight for the given trajectory
     */
    private function findHits($inputFile, $maxY, $maxX, $onHit) {
        $targetArea = $this->readInput($inputFile);
        for ($speedY = -$maxY; $speedY <= $maxY; $speedY++) {
            for ($speedX = 1; $speedX <= $maxX; $speedX++) {
                $maxForTrajectory = $this->trajectoryHits($speedX, $speedY, $targetArea);
                if ($maxForTrajectory !== false) {
                    echo "[$speedX,$speedY] hits with $maxForTrajectory\n";
                    $onHit($maxForTrajectory);
                } else {
                    echo "[$speedX,$speedY] misses\n";
                }
            }
        }

    }

    /**
     * https://adventofcode.com/2021/day/17
     * @param $inputFile
     * @return int max height reached in the trajectories that hit tge the target
     */
    public function question1($inputFile, $upperY = 400, $upperX = 400): int {
        $maxY = 0;
        $this->findHits($inputFile, $upperY, $upperX, function($height) use(&$maxY) {
            $maxY = max($maxY, $height);
        });
        return $maxY;
    }

    /**
     * https://adventofcode.com/2021/day/17
     * @param $inputFile
     * @return int number of trajectories hitting
     */
    public function question2($inputFile, $upperY = 400, $upperX = 400): int {
        $count = 0;
        $this->findHits($inputFile, $upperY, $upperX, function($height) use(&$count) {
            $count++;
        });
        return $count;
    }

}