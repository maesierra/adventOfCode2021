<?php

use maesierra\AdventOfCode2021\Day1;
use maesierra\AdventOfCode2021\Day10;
use maesierra\AdventOfCode2021\Day11;
use maesierra\AdventOfCode2021\Day12;
use maesierra\AdventOfCode2021\Day13;
use maesierra\AdventOfCode2021\Day14;
use maesierra\AdventOfCode2021\Day15;
use maesierra\AdventOfCode2021\Day16;
use maesierra\AdventOfCode2021\Day17;
use maesierra\AdventOfCode2021\Day18;
use maesierra\AdventOfCode2021\Day19;
use maesierra\AdventOfCode2021\Day2;
use maesierra\AdventOfCode2021\Day20;
use maesierra\AdventOfCode2021\Day21;
use maesierra\AdventOfCode2021\Day22;
use maesierra\AdventOfCode2021\Day23;
use maesierra\AdventOfCode2021\Day24;
use maesierra\AdventOfCode2021\Day25;
use maesierra\AdventOfCode2021\Day3;
use maesierra\AdventOfCode2021\Day4;
use maesierra\AdventOfCode2021\Day5;
use maesierra\AdventOfCode2021\Day6;
use maesierra\AdventOfCode2021\Day7;
use maesierra\AdventOfCode2021\Day8;
use maesierra\AdventOfCode2021\Day9;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

require __DIR__ . '/../vendor/autoload.php';

if (php_sapi_name() != "cli") {
    die ("Command line only<br/>");
}


$app = new Application("advent-of-code-2021");

/**
 * @param $day int
 * @param $question int
 * @param $run \Closure
 * @return Command
 */
function runQuestion($day, $question, $run)
{
    return new class($day, $question, $run) extends Command {
        /** @var int */
        private $day;
        /** @var int */
        private $question;
        /** @var Closure */
        private $run;

        public function __construct($day, $question, $run) {
            parent::__construct("day{$day}-question{$question}");
            $this->day = $day;
            $this->question = $question;
            $this->run = $run;
        }

        protected function configure()
        {
            $this->setDescription("Runs day {$this->day} question {$this->question}")
                ->addArgument(
                    'file',
                    InputArgument::OPTIONAL,
                    'File to process'
                );

        }

        public function execute(InputInterface $input, OutputInterface $output) {
            $r = $this->run;
            $result = $r($input->getArgument("file"));
            $output->writeln("Result is " . $result);
        }
    };
}
$daySolutions = [
    1 => new Day1(),
    2 => new Day2(),
    3 => new Day3(),
    4 => new Day4(),
    5 => new Day5(),
    6 => new Day6(),
    7 => new Day7(),
    8 => new Day8(),
    9 => new Day9(),
    10 => new Day10(),
    11 => new Day11(),
    12 => new Day12(),
    13 => new Day13(),
    14 => new Day14(),
    15 => new Day15([]),
    16 => new Day16(),
    17 => new Day17(),
    18 => new Day18(),
    19 => new Day19(),
    20 => new Day20(),
    21 => new Day21(),
    22 => new Day22(),
    23 => new Day23(),
    24 => new Day24(),
    25 => new Day25(),

];
foreach (range(1, 25) as $day) {
    $app->add(runQuestion($day, 1, function($file) use($daySolutions, $day) {
        return $daySolutions[$day]->question1($file ?: "input_day$day.txt");
    }));
    $app->add(runQuestion($day, 2, function($file) use($daySolutions, $day) {
        return $daySolutions[$day]->question2($file ?: "input_day$day.txt");
    }));
}
$app->run();