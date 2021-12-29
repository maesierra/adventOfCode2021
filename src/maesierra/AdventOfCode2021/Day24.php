<?php


namespace maesierra\AdventOfCode2021;


use Exception;
use maesierra\AdventOfCode2021\Day24\ALU;
use maesierra\AdventOfCode2021\Day24\Tile;

class Day24 {

    /** @var string[][] */
    private $blocks;

    /** @var ALU */
    private $alu;

    /** @var bool  */
    private $invert = false;
    private $outputPrefix = '';
    private $inputFile;


    /**
     * @param string $inputFile
     * @return int
     * @throws Exception
     */
    public function question1(string $inputFile, $params = []):int {
        $solutions = $this->solve($inputFile, $params);
        return max(array_values($solutions));

    }

    /**
     * @param string $inputFile
     * @return int
     * @throws Exception
     */
    public function question2(string $inputFile, $params = []): int {
        $this->invert = true;
        $this->outputPrefix = ".2";
        $solutions = $this->solve($inputFile, $params);
        return min(array_values($solutions));
    }


    /**
     * @param string $inputFile
     * @param array $params
     * @return array
     */
    private function solve(string $inputFile, array $params) {
        $this->alu = new ALU();
        $this->inputFile = $inputFile;
        $instructions = explode("\n", file_get_contents($inputFile));
        $startingBlock = 14;

        $this->blocks = array_chunk($instructions, 18);
        //Last block will only return 0 if z between 0 and 25 (because of z div 26)
        $solutions = [];
        if (isset($params['param1'])) {
            $startingBlock = $params['param1'];
            $solutions = json_decode(file_get_contents($this->outputFilename($startingBlock)), true);
        }
        if ($startingBlock > 13) {
            echo "Solving block 13...\n";
            foreach (range(0, 25) as $z) {
                $runAlu = $this->runBlock($z, 13);
                foreach ($runAlu as $n => $res) {
                    if ($res == 0) {
                        $solutions[$z] = $n;
                    }
                }
            }
            $this->saveStep(13, $solutions);
        }
        $solutions = $this->solveDigit(12, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(11, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(10, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(9, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(8, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(7, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(6, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(5, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(4, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(3, 'div', $startingBlock, $solutions);
        $solutions = $this->solveDigit(2, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(1, 'mul', $startingBlock, $solutions);
        $solutions = $this->solveDigit(0, 'mul', $startingBlock, $solutions);
        return $solutions;
    }

    private function runBlock($z, $nBlock) {
        $res = [];
        foreach ([1, 2, 3, 4, 5, 6, 7, 8, 9] as $n) {
            $this->alu->reset();
            $this->alu->x = 0;
            $this->alu->y = 0;
            $this->alu->z = $z;
            $this->alu->run($this->blocks[$nBlock], [$n]);
            $res[$n] = $this->alu->z;
        }
        return $res;
    }

    private function solveBlockWithDiv26($nBlock, $prevBlockSolutions) {
        //Because of the z div 26 we're only interested in z values that after dividing by 26 give one
        //of the previous block z values.
        //So we multiply by 26 the whole range to have all the possible candidates
        $candidatesZ = range(min(array_keys($prevBlockSolutions)) * 26, max(array_keys($prevBlockSolutions)) * 26);
        return $this->solveBlock($candidatesZ, $nBlock, $prevBlockSolutions);
    }

    private function solveBlockWithMul26($nBlock, $prevBlockSolutions) {
        //Because of the multiplication by 26 we're only interested in z values that after multiplying by 26 give one
        //of the previous block z values.
        //So we divide by 26 the whole range to have all the possible candidates
        $candidatesZ = $candidatesZ = range((int)(min(array_keys($prevBlockSolutions)) / 26), (int)(max(array_keys($prevBlockSolutions)) / 26));;
        return $this->solveBlock($candidatesZ, $nBlock, $prevBlockSolutions);
    }

    private function selectSolution($solutions, $z, $digits) {
        if (isset($solutions[$z])) {
            return $this->invert ? min($solutions[$z], $digits) : max($solutions[$z], $digits);
        } else {
            return $digits;
        }
    }

    /**
     * @param array $candidatesZ
     * @param $nBlock
     * @param $prevBlockSolutions
     * @return array[]
     */
    private function solveBlock(array $candidatesZ, $nBlock, $prevBlockSolutions): array
    {
        $solutions = [];
        foreach ($candidatesZ as $pos => $z) {
            echo "Candidate $pos(" . count($candidatesZ) . ")\n";
            foreach ($this->runBlock($z, $nBlock) as $n => $res) {
                if (isset($prevBlockSolutions[$res])) {
                    $solutions[$z] = $this->selectSolution($solutions, $z, $n . $prevBlockSolutions[$res]);
                }
            }
        }
        return $solutions;
    }
    /**
     * @param int $nBlock
     * @param string $type
     * @param int $startingBlock
     * @param array $solutions
     * @return array
     */
    private function solveDigit(int $nBlock, string $type, int $startingBlock, array $solutions): array
    {
        if ($startingBlock > $nBlock) {
            echo "Solving block $nBlock...\n";
            $solutions =
                ($type == 'div') ?
                    $this->solveBlockWithDiv26($nBlock, $solutions) :
                    $this->solveBlockWithMul26($nBlock, $solutions);
            $this->saveStep($nBlock, $solutions);
        }
        return $solutions;
    }

    /**
     * @param int $nBlock
     * @param array $solutions
     * @return false|int
     */
    private function saveStep(int $nBlock, array $solutions) {
        return file_put_contents($this->outputFilename($nBlock), json_encode($solutions));
    }

    /**
     * @param int $nBlock
     * @return string
     */
    private function outputFilename(int $nBlock): string
    {
        return $this->inputFile . "{$this->outputPrefix}.$nBlock.json";
    }

}
