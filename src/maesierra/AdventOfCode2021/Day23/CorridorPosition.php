<?php


namespace maesierra\AdventOfCode2021\Day23;


class CorridorPosition extends Location {


    /** @var Amphipod */
    public $occupant = null;

    public function contains(Amphipod $amphipod): bool {
        return $this->occupant && $this->occupant->id == $amphipod->id;
    }

    public function remove(): ?Amphipod {
        $occupant = $this->occupant;
        $this->occupant = null;
        return $occupant;
    }

    public function moveIn(Amphipod $amphipod) {
        $this->occupant = $amphipod;
    }

    public function id() {
        return "H{$this->position}";
    }

    public function exitPosition(): int {
        return $this->position;
    }

    /**
     * @return Amphipod[]
     */
    public function occupants(): array {
        return $this->occupant ? [$this->occupant] : [];
    }

    public function innerDistance(Amphipod $amphipod): int {
        return 0;
    }
}