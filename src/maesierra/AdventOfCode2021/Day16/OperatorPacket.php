<?php


namespace maesierra\AdventOfCode2021\Day16;


class OperatorPacket extends Packet
{

    const LENGTH_TYPE_0 = 0;
    const LENGTH_TYPE_1 = 1;

    public $lengthTypeId = null;
    /** @var Packet[] */
    public $children = [];

    /** @var bool|int */
    public $length = false;

    public function __construct($type) {
        $this->type = $type;
    }

    public function hasLengthSet() {
        return $this->length !== false;
    }

    /**
     * @return int
     */
    public function lengthSize() {
        switch ($this->lengthTypeId) {
            case self::LENGTH_TYPE_0:
                return 15;
            case self::LENGTH_TYPE_1:
                return 11;
        }
        return 0;
    }

    public function versionSum() {
        return parent::versionSum() + array_reduce($this->children, function ($sum, $p) {
                /** @var Packet $p */
                return $p->versionSum() + $sum;
            }, 0);
    }


    public function isCompleted() {
        switch ($this->lengthTypeId) {
            case self::LENGTH_TYPE_0:
                return parent::isCompleted();
            case self::LENGTH_TYPE_1:
                return $this->hasLengthSet() && count($this->children) == $this->length;
        }
    }

}