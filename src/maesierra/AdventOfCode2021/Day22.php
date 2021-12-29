<?php


namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day22\Cuboid;

class Day22 {

    /**
     * @param string $inputFile
     * @return Cuboid[]
     */
    private function readRebootInstructions(string $inputFile) {
        return array_reduce(explode("\n", file_get_contents($inputFile)),
            function (&$result, $line) {
                $line = trim($line);
                if ($line) {
                    if (preg_match('/^(on|off) x=(-?\d+)\.\.(-?\d+),y=(-?\d+)\.\.(-?\d+),z=(-?\d+)\.\.(-?\d+)$/', $line, $matches)) {
                        $result[] = new Cuboid($matches[1] == "on", (int)$matches[2], (int)$matches[4], (int)$matches[6], (int)$matches[3], (int)$matches[5], (int)$matches[7]);
                    } return $result;
                }
                return $result;
            }
            , []
        );
    }


    public function question1(string $inputFile, $params = [], $cuboids = []):int {
        if (!$cuboids) {
            $cuboids = $this->readRebootInstructions($inputFile);
        }
        $region = new Cuboid( false, -50, -50, -50, 50, 50, 50);
        $cuboids = array_filter($cuboids, function ($c) use($region) {
            return $region->intersect($c);
        });
        return $this->apply($cuboids);
    }

    public function question2(string $inputFile, $params = [], $cuboids = []):int {
        if (!$cuboids) {
            $cuboids = $this->readRebootInstructions($inputFile);
        }
        return $this->apply($cuboids);
    }

    /**
     * @param array $cuboids
     * @return mixed
     */
    private function countActive(array $cuboids)
    {
        return array_reduce($cuboids, function ($sum, $c) {
            /** @var $c Cuboid */
            $v =  ($c->toX + 1 - $c->fromX) *
		          ($c->toY + 1 - $c->fromY) *
                  ($c->toZ + 1 - $c->fromZ);
            return $v + $sum;
        }, 0);
    }

    /**
     * @param array $cuboids
     * @return mixed
     */
    private function apply(array $cuboids)
    {
        /** @var Cuboid[] $on */
        $on = [array_shift($cuboids)];
        $pos = 0;
        foreach ($cuboids as $pos => $cuboid) {
            echo "{$pos}: ".Cuboid::implode($on)."\n";
            /** @var Cuboid[] $subtract */
            $subtract = [];
            foreach ($on as $c) {
                $subtract = array_merge($subtract, $c->substract($cuboid));
            }
            $on = $subtract;
            if ($cuboid->state) {
                $on = array_merge([$cuboid], $on);
            }
//            $a = $this->countActive($on);
//            echo "$a\n";
        }
        echo "{$pos}: ".Cuboid::implode($on)."\n";
        return $this->countActive($on);
    }
}