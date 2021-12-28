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



    /**
     * Runs the instructions from the given block with an ALU initialised to z (x and y to 0) for all the 9
     * possible input for the last input
     * @param int[] $completed digits => starting z
     * @param int[] $blockSolutions z => digit all the z values that have solved the block
     * @param int $nBlock
     * @return array digits => starting z with all the values that after running from the given block end up with z = 0
     */
    private function runFromBlock(array $completed, $z, $n, int $nBlock) {
        $res = [];
        $block = [];
        for ($i = $nBlock; $i < count($this->blocks); $i++) {
            $block = array_merge($block, $this->blocks[$i]);
        }
            foreach (array_keys($completed) as $numbers) {
                $this->alu->reset();
                $this->alu->x = 0;
                $this->alu->y = 0;
                $this->alu->z = $z;
                $this->alu->run($block, str_split("$n$numbers"));
                if ($this->alu->z == 0) {
                    $res["$n$numbers"] = $z;
                }
            }
        return $res;
    }

    private function runBlock($z, $nBlock) {
        $res = [];
        $block = $this->blocks[$nBlock];
        foreach ([1,2,3,4,5,6,7,8,9]  as $n) {
            $this->alu->reset();
            $this->alu->x = 0;
            $this->alu->y = 0;
            $this->alu->z = $z;
            $this->alu->run($block, [$n]);
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

    /**
     * @param string $inputFile
     * @return int
     * @throws Exception
     */
    public function question1(string $inputFile, $params = []):int {
        $this->alu = new ALU();
        $instructions = explode("\n", file_get_contents($inputFile));
        $startingBlock = 14;

        $this->blocks = array_chunk($instructions, 18);
        //Last block will only return 0 if z between 0 and 25 (because of z div 26)
        $solutions = [];
        if (isset($params['param1'])) {
            $startingBlock = $params['param1'];
            echo "loading $inputFile.$startingBlock.json\n";
            $solutions = json_decode(file_get_contents("$inputFile.$startingBlock.json"), true);
            print_r($solutions);
        }
        if ($startingBlock > 13) {
            echo "Solving block 13...\n";
            foreach (range(0, 25) as $z) {
                $runAlu = $this->runBlock($z, 13);
                foreach ($runAlu as $n => $res) {
                    if ($res == 0) {
                        $completed["$n"] = $z;
                        $solutions[$z] = $n;
                    }
                }
            }
            file_put_contents($inputFile.".13.json", json_encode($completed));
        }
        $solutions = $this->solveDigit(12, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(11, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(10, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(9, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(8, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(7, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(6, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(5, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(4, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(3, 'div', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(2, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(1, 'mul', $startingBlock, $solutions, $inputFile);
        $solutions = $this->solveDigit(0, 'mul', $startingBlock, $solutions, $inputFile);
        return max(array_values($solutions));

    }

    /**
     * @param string $inputFile
     * @param int $nDays
     * @return int
     * @throws Exception
     */
    public function question2(string $inputFile): int {

    }

    /**
     * @param array $instructions
     * @return array
     */
    private function nextInputBlock($instructions): array {
        $block = [];
        foreach ($instructions as $pos => $instruction) {
            if ($pos > 0 && strpos($instruction, "inp") !== false) {
                return [$block, array_slice($instructions, $pos)];
            }
            $block[] = $instruction;
        }
        return [$instructions, []];
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
                    $solutions[$z] = $n.$prevBlockSolutions[$res];
                }
            }
        }
        return $solutions;
    }

    /**
     * @param int $startingBlock
     * @param array $solutions
     * @param string $inputFile
     * @return array
     */
    private function solveDigit(int $nBlock, string $type, int $startingBlock, array $solutions, string $inputFile): array
    {
        if ($startingBlock > $nBlock) {
            echo "Solving block $nBlock...\n";
            $solutions =
                ($type == 'div') ?
                    $this->solveBlockWithDiv26($nBlock, $solutions) :
                    $this->solveBlockWithMul26($nBlock, $solutions);
            file_put_contents($inputFile . ".$nBlock.json", json_encode($solutions));
        }
        return $solutions;
    }

}
