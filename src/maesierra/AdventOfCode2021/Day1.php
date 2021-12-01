<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;


class Day1 {



    /**
     * Count the number of times a depth measurement increases from the previous measurement. (There is no measurement before the first measurement.)
     *
     * @param $inputFile string file containing a number per line
     * @return int the number of times a depth measurement increases from the previous measurement
     * @throws \Exception
     */
    public function question1($inputFile) {
        $measures = explode("\n", file_get_contents($inputFile));
        $last = -1;
        $res = 0;
        foreach ($measures as $pos => $measure) {
            if ($pos != 0) {
                if ($measure > $last) {
                    $res++;
                }
            }
            $last = $measure;
        }
        return $res;
    }

    public function question2(string $inputFile) {
        $measures = explode("\n", file_get_contents($inputFile));
        $res = 0;
        $windowSize = 3;
        $pos = $windowSize;
        $window = array_slice($measures, 0, $windowSize);
        $last = array_sum($window);
        while ($pos < count($measures)) {
            array_shift($window);
            $window[] = $measures[$pos];
            $pos++;
            $current = array_sum($window);
            if ($current > $last) {
                $res++;
            }
            $last = $current;
        }
        return $res;
    }
}