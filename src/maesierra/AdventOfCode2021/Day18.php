<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;



use maesierra\AdventOfCode2021\Day18\SnailfishNumber;

class Day18 {


    /**
     * @param $inputFile string
     * @return SnailfishNumber[]
     */
    private function readInput(string $inputFile): array {
        return array_reduce(explode("\n", trim(file_get_contents($inputFile))),
            function (&$result, $line) {
                $line = trim($line);
                if (!$line) {
                    return $result;
                }
                $result[] = SnailfishNumber::parse($line);
                return $result;
            }, []);
    }



    public function question1(string $inputFile):int {
        $numbers = $this->readInput($inputFile);
        /** @var SnailfishNumber $result */
        $result = array_reduce($numbers, function($res, $number) {
            /** @var $res SnailfishNumber */
            /** @var $number SnailfishNumber */
            if (!$res) {
                return $number;
            } else {
                return $res->add($number);
            }
        }, null);
        echo "Result is $result\n";
        return $result->magnitude();
    }

    public function question2(string $inputFile):int {
        $numbers = array_filter(explode("\n", trim(file_get_contents($inputFile))));
        $max = 0;
        $n = count($numbers);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    $n1 = SnailfishNumber::parse($numbers[$i]);
                    $n2 = SnailfishNumber::parse($numbers[$j]);
                    $magnitude = $n1->add($n2)->magnitude();
                    echo "$i:$j\n";
                    $max = max($max, $magnitude);
                }
            }
        }
        return $max;
    }


}