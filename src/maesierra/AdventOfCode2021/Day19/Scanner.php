<?php


namespace maesierra\AdventOfCode2021\Day19;


class Scanner
{
    /** @var Position */
    public $position;

    /** @var Position[] */
    public $beacons = [];

    /** @var array */
    private $histogram = [];

    public $id;

    /**
     * Scanner constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return "scanner {$this->id}";
    }

    public function addBeacon(string $x, string $y, string $z)
    {
        $position = new Position($x, $y, $z);
        $this->histogram[$x]++;
        $this->histogram[$y]++;
        $this->histogram[$z]++;
        $this->beacons["$position"] = $position;
    }

    public function setBeacons($beacons)
    {
        $this->histogram = [];
        $this->beacons = [];
        foreach ($beacons as $beacon) {
            $this->addBeacon($beacon->x, $beacon->y, $beacon->z);
        }
    }

    public function isLocated()
    {
        return !is_null($this->position);
    }

    /**
     * @param $beacons Position[]
     * @return Offset
     */
    private function tryOffset($beacons, $srcBeacon, $destBeacon) {
        //translate dest to all orientations and calculate offsets
        $orientations = Orientation::all();
        $offsets = array_reduce($orientations, function(&$res, $o) use($srcBeacon, $destBeacon) {
            /** @var Orientation $o */
            $destApplied = $o->apply($destBeacon->x, $destBeacon->y, $destBeacon->z);
            $destApplied[0] = $srcBeacon->x - $destApplied[0];
            $destApplied[1] = $srcBeacon->y - $destApplied[1];
            $destApplied[2] = $srcBeacon->z - $destApplied[2];
            $res[] = $destApplied;
           return $res;
        });
        foreach ($offsets as $i => $offset) {
            $orientation = $orientations[$i];
            list($offsetX, $offsetY, $offsetZ) = $offset;
            $applied = array_map(function($beacon) use($orientation, $offsetX, $offsetY, $offsetZ) {
                list($x, $y, $z) = $orientation->apply($beacon->x, $beacon->y, $beacon->z, $offsetX, $offsetY, $offsetZ);
                return "$x:$y:$z";
            }, $beacons);
            $diff = array_intersect(array_keys($this->beacons), array_values($applied));
            if (count($diff) >= 12) {
                return new Offset($offsetX, $offsetY, $offsetZ, $orientation);
            }

        }
        return null;
    }

    /**
     * @param $position Position
     * @param $orientation Orientation
     * @param $offsetX int
     * @param $offsetY int
     * @param $offsetZ int
     * @return bool
     */
    public function containsBeacon($position, $orientation, $offsetX, $offsetY, $offsetZ): bool
    {
        $coords = [$position->x, $position->y, $position->z];
        $x = ($coords[($orientation->upCoordinate + 0) % 3] * $orientation->orientationX) + $offsetX;
        $y = ($coords[($orientation->upCoordinate + 1) % 3] * $orientation->orientationY) + $offsetY;
        $z = ($coords[($orientation->upCoordinate + 2) % 3] * $orientation->orientationZ) + $offsetZ;
        return isset($this->beacons["$x:$y:$z"]);
    }

    public function overlaps(Scanner $scanner)
    {
        echo "Checking scanner {$this->id} against {$scanner->id}\n";
        foreach ($this->beacons as $i => $srcBeacon) {
            echo "Checking if src beacon $i can be in {$scanner->id}\n";
            foreach ($scanner->beacons as $j => $destBeacon) {
                $offset = $this->tryOffset($scanner->beacons, $srcBeacon, $destBeacon);
                if ($offset) {
                    return $offset;
                }

            }
        }
        return null;
    }
}