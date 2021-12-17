<?php
/**
 * Created by PhpStorm.
 * User: maesierra
 * Date: 01/12/2020
 * Time: 20:46
 */

namespace maesierra\AdventOfCode2021;


use maesierra\AdventOfCode2021\Day16\EqualTo;
use maesierra\AdventOfCode2021\Day16\GreaterThan;
use maesierra\AdventOfCode2021\Day16\LessThan;
use maesierra\AdventOfCode2021\Day16\Maximum;
use maesierra\AdventOfCode2021\Day16\Minimum;
use maesierra\AdventOfCode2021\Day16\OperatorPacket;
use maesierra\AdventOfCode2021\Day16\Packet;
use maesierra\AdventOfCode2021\Day16\ParseResult;
use maesierra\AdventOfCode2021\Day16\Literal;
use maesierra\AdventOfCode2021\Day16\Product;
use maesierra\AdventOfCode2021\Day16\Sum;

class Day16 {

    /**
     * @param $inputFile string
     * @return string[]
     */
    private function readInput(string $inputFile): array {
        $hexStream = trim(file_get_contents($inputFile));
        return array_reduce(str_split($hexStream), function(&$bin, $hex) {
            $toBinary = str_pad(base_convert($hex, 16, 2), 4, "0", STR_PAD_LEFT);
            return array_merge($bin, str_split($toBinary));
        }, []);
    }

    /**
     * @param $binaryStream
     * @param $startingPos
     * @return ParseResult
     */
    public function parsePacket($binaryStream, $startingPos = 0) {
        $len = count($binaryStream);
        $version = null;
        /** @var Packet|Literal|OperatorPacket $current */
        $current = null;
        $currentBlock = '';
        $id = uniqid();
        $pos = $startingPos;
        while ($pos < $len) {
            $bit = $binaryStream[$pos];
            $currentBlock .= $bit;
            $blockLength = strlen($currentBlock);
            $hasVersion = !is_null($version);
            if (!$hasVersion && $blockLength == 3) {
                $version = base_convert($currentBlock, 2, 10);
                echo "$id-$pos: Packet started version $version\n";
                $currentBlock = '';
            } else if ($hasVersion && !$current && $blockLength == 3) {
                $type = base_convert($currentBlock, 2, 10);
                $current = $this->createPacketByType($type);
                echo "$id-$pos: type $type\n";
                $current->version = (int) $version;
                $currentBlock = '';
            } else if ($hasVersion && $current) {
                $isOperator = $current instanceof OperatorPacket;
                if (!$isOperator) {
                    if ($current->type == Packet::TYPE_LITERAL && $blockLength == 5) {
                        echo "$id-$pos: adding literal bits $currentBlock\n";
                        $current->addLiteralBits($currentBlock);
                        $currentBlock = '';
                    }
                } else {
                    if (is_null($current->lengthTypeId) && $blockLength == 1) {
                        $current->lengthTypeId = (int)$currentBlock;
                        echo "$id-$pos: lengthTypeId {$current->lengthTypeId}\n";
                        $currentBlock = '';
                    } else if (!$current->hasLengthSet() && $blockLength == $current->lengthSize()) {
                        $current->length = base_convert($currentBlock, 2, 10);
                        echo "$id-$pos: length {$current->length}\n";
                        $currentBlock = '';
                    } else if ($current->hasLengthSet() && $current->lengthTypeId == OperatorPacket::LENGTH_TYPE_0) {
                        $startingSubPacket = $pos;
                        while ($pos - $startingSubPacket < $current->length) {
                            echo "$id-$pos: type 0 adding child package $pos of ".($pos + $current->length)."\n";
                            $pos = $this->addSubpacket($binaryStream, $pos, $current);
                        }
                        $current->complete();
                        $currentBlock = '';
                    } else if ($current->hasLengthSet() && $current->lengthTypeId == OperatorPacket::LENGTH_TYPE_1) {
                        $count = 0;
                        while ($count < $current->length) {
                            $count++;
                            echo "$id-$pos: type 1 adding child package $count of ".$current->length."\n";
                            $pos = $this->addSubpacket($binaryStream, $pos, $current);
                        }
                        $current->complete();
                    }
                }
                if ($current->isCompleted()) {
                    echo "$id-$pos: block completed\n";
                    return new ParseResult($current, $pos);
                }
            }
            $pos++;
        }
        return null;
    }


    /**
     * Read all the rules and tickets, ignore first ticket and sum all the invalid fields
     *
     * @param $inputFile string file
     * @return int
     */
    public function question1($inputFile) {
        $packet = $this->parsePacket($this->readInput($inputFile))->packet;
        return $packet->versionSum();
    }

    public function question2(string $inputFile) {
        $packet = $this->parsePacket($this->readInput($inputFile))->packet;
        return $packet->value;
    }

    /**
     * @param $binaryStream
     * @param int $pos
     * @param OperatorPacket $current
     * @return int
     */
    private function addSubpacket($binaryStream, int $pos, $current): int {
        $parseResult = $this->parsePacket($binaryStream, $pos);
        $current->children[] = $parseResult->packet;
        $pos = $parseResult->pos + ($parseResult->packet instanceof Literal ? 1 : 0);
        return $pos;
    }

    /**
     * @param string $type
     * @return Packet
     */
    private function createPacketByType(string $type): Packet
    {
        switch ($type) {
            case Sum::TYPE_SUM:
                return new Sum();
            case Product::TYPE_PRODUCT:
                return new Product();
            case Maximum::TYPE_MAXIMUM:
                return new Maximum();
            case Minimum::TYPE_MINIMUM:
                return new Minimum();
            case GreaterThan::TYPE_GREATER_THAN:
                return new GreaterThan();
            case LessThan::TYPE_LESS_THAN:
                return new LessThan();
            case EqualTo::TYPE_EQUAL_TO:
                return new EqualTo();
            case Literal::TYPE_LITERAL:
                return new Literal();
            default:
                return new OperatorPacket($type);
        }
    }

}