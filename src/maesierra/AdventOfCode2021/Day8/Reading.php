<?php


namespace maesierra\AdventOfCode2021\Day8;


class Reading {
    const DIGITS = [
        0 => 'abcefg',
        1 => 'cf',
        2 => 'acdeg',
        3 => 'acdfg',
        4 => 'bcdf',
        5 => 'abdfg',
        6 => 'abdefg',
        7 => 'acf',
        8 => 'abcdefg',
        9 => 'abcdfg',
    ];
    const DIGITS_INVERTED = [
        'abcefg' => 0,
        'cf' => 1,
        'acdeg' => 2,
        'acdfg' => 3,
        'bcdf' => 4,
        'abdfg' => 5,
        'abdefg' => 6,
        'acf' => 7,
        'abcdefg' => 8,
        'abcdfg' => 9,
    ];
    const SIZE_MAP = [
        0 => 6,
        1 => 2,
        2 => 5,
        3 => 6,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 3,
        8 => 7,
        9 => 6
    ];

    /** @var string[] */
    public $input;
    /** @var string[] */
    public $output;

    /**
     * Reading constructor.
     * @param string[] $input
     * @param string[] $output
     */
    public function __construct($input, $output)
    {
        $this->input = $input;
        $this->output = $output;
    }

    /**
     * @param $size  int
     * @return int[]
     */
    private function inputBySize(int $size):array {
        return array_values(array_filter($this->input, function($v) use($size) {
            return strlen($v) == $size;
        }));
    }

    public function translate() {
        $options = array_reduce([1, 7, 4, 8], function (&$map, $digit) {
            $alreadyOnMap = array_reduce(array_values($map), function($res, $v) {
                return array_unique(array_merge($res, $v));
            }, []);
            $options = str_split($this->inputBySize(self::SIZE_MAP[$digit])[0]);
            //Remove any option already on the map
            $options = array_diff($options, $alreadyOnMap);
            foreach (str_split(self::DIGITS[$digit]) as $segment) {
                if ($map[$segment]) {
                    continue;
                }
                $map[$segment] = array_merge($map[$segment], $options);
            }
            return $map;
        }, [
            'a' => [],
            'b' => [],
            'c' => [],
            'd' => [],
            'e' => [],
            'f' => [],
            'g' => [],
        ]);
        $options = array_map(function($v) {
            return count($v) == 1 ? $v[0] : $v;
        }, $options);
        foreach ($options as $segment => $segmentOptions) {
            if (!is_array($segmentOptions)) {
                continue;
            }
            foreach ($segmentOptions as $option) {
                $attempt = $options;
                $solution = $this->solve($segment, $option, $attempt);
                if ($solution) {
                    $translated = array_map(function ($digit) use ($solution) {
                        return self::DIGITS_INVERTED[$this->translateDigit($digit, $solution)];
                    }, $this->output);
                    return (int) implode("", $translated);
                }
            }
        }
        return 0;
    }

    /**
     * @param $segment
     * @param $option
     * @param $attempt
     * @return bool|array
     */
    private function solve($segment, $option, $attempt) {
        $attempt[$segment] = $option;
        $notMapped = array_filter($attempt, function ($v) {
            return is_array($v);
        });
        $count = count($notMapped);
        //Remove the option from the rest
        foreach ($notMapped as $segment => $options) {
            $pos = array_search($option, $options);
            if ($pos !== false) {
                unset($attempt[$segment][$pos]);
                $attempt[$segment] = array_values($attempt[$segment]);
                if (count($attempt[$segment]) == 1) {
                    $attempt[$segment] = $attempt[$segment][0];
                    unset($notMapped[$segment]);
                    $count--;
                }
            }
        }
        if ($count == 0) {
            $implode = implode("", array_values($attempt));
            foreach ($this->input as $digit) {
                $translated = $this->translateDigit($digit, $attempt);
                if (!isset(self::DIGITS_INVERTED[$translated])) {
                    return false;
                }
            }
            return $attempt;
        } else {
            $next = array_keys($notMapped)[0];
            foreach ($notMapped[$next] as $o) {
                $map = $this->solve($next, $o, $attempt);
                if ($map) {
                    return $map;
                }
            }
            return false;

        }
    }

    /**
     * @param string $digit
     * @param $attempt
     * @return string
     */
    private function translateDigit(string $digit, $attempt): string
    {
        $translated = strtr($digit, implode("", array_values($attempt)), 'abcdefg');
        //Put the translated into alphabetical order
        $chars = str_split($translated);
        sort($chars);
        $translated = implode("", $chars);
        return $translated;
    }


}