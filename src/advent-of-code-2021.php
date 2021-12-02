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

$app->add(runQuestion(1, 1, function($file) {
    return (new Day1())->question1($file ?: "input_day1.txt");
}));
$app->add(runQuestion(1, 2, function($file) {
    return (new Day1())->question2($file ?: "input_day1.txt");
}));
$app->add(runQuestion(2, 1, function($file) {
    return (new Day2())->question1($file ?: "input_day2.txt");
}));
$app->add(runQuestion(2, 2, function($file) {
    return (new Day2())->question2($file ?: "input_day2.txt");
}));
$app->add(runQuestion(3, 1, function($file) {
    return (new Day3())->question1($file);
}));
$app->add(runQuestion(3, 2, function($file) {
    return (new Day3())->question2($file);
}));
$app->add(runQuestion(4, 1, function($file) {
    return (new Day4())->question1($file);
}));
$app->add(runQuestion(4, 2, function($file) {
    return (new Day4())->question2($file);
}));
$app->add(runQuestion(5, 1, function($file) {
    return (new Day5())->question1($file);
}));
$app->add(runQuestion(5, 2, function($file) {
    return (new Day5())->question2($file);
}));
$app->add(runQuestion(6, 1, function($file) {
    return (new Day6())->question1($file);
}));
$app->add(runQuestion(6, 2, function($file) {
    return (new Day6())->question2($file);
}));
$app->add(runQuestion(7, 1, function($file) {
    return (new Day7())->question1($file);
}));
$app->add(runQuestion(7, 2, function($file) {
    return (new Day7())->question2($file);
}));
$app->add(runQuestion(8, 1, function($file) {
    return (new Day8())->question1($file);
}));
$app->add(runQuestion(8, 2, function($file) {
    return (new Day8())->question2($file);
}));
$app->add(runQuestion(9, 1, function($file) {
    return (new Day9())->question1($file, 25);
}));
$app->add(runQuestion(9, 2, function($file) {
    return (new Day9())->question2($file, 675280050);
}));
$app->add(runQuestion(10, 1, function($file) {
    return (new Day10())->question1($file);
}));
$app->add(runQuestion(10, 2, function($file) {
    return (new Day10())->question2($file);
}));
$app->add(runQuestion(11, 1, function($file) {
    return (new Day11())->question1($file);
}));
$app->add(runQuestion(11, 2, function($file) {
    return (new Day11())->question2($file);
}));
$app->add(runQuestion(12, 1, function($file) {
    return (new Day12())->question1($file);
}));
$app->add(runQuestion(12, 2, function($file) {
    return (new Day12())->question2($file);
}));
$app->add(runQuestion(13, 1, function($file) {
    $res = (new Day13())->question1($file);
    $id = array_keys($res)[0];
    $wait = array_values($res)[0];
    echo "BUS $id => $wait\n";
    return $id * $wait;
}));
$app->add(runQuestion(13, 2, function($file) {
    return (new Day13())->question2($file);
}));
$app->add(runQuestion(14, 1, function($file) {
    return (new Day14())->question1($file);
}));
$app->add(runQuestion(14, 2, function($file) {
    return (new Day14())->question2($file);
}));
$app->add(runQuestion(15, 1, function($file) {
    return (new Day15([13,0,10,12,1,5,8]))->nThPosition(2020);
}));
$app->add(runQuestion(15, 2, function($file) {
    return (new Day15([13,0,10,12,1,5,8]))->nThPosition(30000000);
}));
$app->add(runQuestion(16, 1, function($file) {
    return (new Day16())->question1($file);
}));
$app->add(runQuestion(16, 2, function($file) {
    return (new Day16())->question2($file);
}));
$app->add(runQuestion(17, 1, function($file) {
    return (new Day17())->question1($file);
}));
$app->add(runQuestion(17, 2, function($file) {
    return (new Day17())->question2($file);
}));
$app->add(runQuestion(18, 1, function($file) {
    return (new Day18())->question1($file);
}));
$app->add(runQuestion(18, 2, function($file) {
    return (new Day18())->question2($file);
}));
$app->add(runQuestion(19, 1, function($file) {
    return (new Day19())->question1($file);
}));
$app->add(runQuestion(19, 2, function($file) {
    return (new Day19())->question2($file);
}));
$app->add(runQuestion(20, 1, function($file) {
    return (new Day20())->question1($file);
}));
$app->add(runQuestion(20, 2, function($file) {
    return (new Day20())->question2($file);
}));
$app->add(runQuestion(21, 1, function($file) {
    return (new Day21())->question1($file);
}));
$app->add(runQuestion(21, 2, function($file) {
    return (new Day21())->question2($file);
}));
$app->add(runQuestion(22, 1, function($file) {
    return (new Day22())->question1($file);
}));
$app->add(runQuestion(22, 2, function($file) {
    return (new Day22())->question2($file);
}));
$app->add(runQuestion(23, 1, function($file) {
    return (new Day23())->question1($file, 100);
}));
$app->add(runQuestion(23, 2, function($file) {
    return (new Day23())->question2($file);
}));
$app->add(runQuestion(24, 1, function($file) {
    return (new Day24())->question1($file);
}));
$app->add(runQuestion(24, 2, function($file) {
    return (new Day24())->question2($file, 100, 200);
}));
$app->add(runQuestion(25, 1, function($file) {
    return (new Day25())->question1($file);
}));
$app->run();