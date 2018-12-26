<?php

namespace Ppx17\Aoc2018\Days\Day24;


class Group
{
    private $id;

    private $unitHitPoints;
    private $unitCount;

    private $attackType;
    private $attackDamage;

    private $initiative;

    private $weaknesses;
    private $immunities;

    private $target;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __toString()
    {
        return sprintf("%s (%s hp, %s units, %s initiative)",
            $this->id,
            $this->getUnitHitPoints(),
            $this->getUnitCount(),
            $this->getEffectiveInitiative());
    }

    public function getUnitHitPoints(): int
    {
        return $this->unitHitPoints;
    }

    public function setUnitHitPoints(int $unitHitPoints): self
    {
        $this->unitHitPoints = $unitHitPoints;
        return $this;
    }

    public function getUnitCount(): int
    {
        return $this->unitCount;
    }

    public function setUnitCount(int $unitCount): self
    {
        $this->unitCount = $unitCount;
        return $this;
    }

    public function getAttackType(): string
    {
        return $this->attackType;
    }

    public function setAttackType(string $attackType): self
    {
        $this->attackType = $attackType;
        return $this;
    }

    public function getAttackDamage(): int
    {
        return $this->attackDamage;
    }

    public function setAttackDamage(int $attackDamage): self
    {
        $this->attackDamage = $attackDamage;
        return $this;
    }

    public function getEffectivePower(): int
    {
        return $this->getAttackDamage() * $this->getUnitCount();
    }

    public function getEffectiveInitiative(): int
    {
        return $this->initiative;
    }

    public function setInitiative(string $initiative): self
    {
        $this->initiative = $initiative;
        return $this;
    }

    public function getWeaknesses(): array
    {
        return $this->weaknesses ?? [];
    }

    public function setWeaknesses(array $weaknesses): self
    {
        $this->weaknesses = $weaknesses;
        return $this;
    }

    public function getImmunities(): array
    {
        return $this->immunities ?? [];
    }

    public function setImmunities(array $immunities): self
    {
        $this->immunities = $immunities;
        return $this;
    }

    public function getTarget(): ?Group
    {
        return $this->target;
    }

    public function setTarget(?Group $target): self
    {
        $this->target = $target;
        return $this;
    }

    public function simulateAttackedBy(Group $attackingGroup): int
    {
        if (in_array($attackingGroup->getAttackType(), $this->getImmunities())) {
            return 0;
        }

        $expectedDamage = $attackingGroup->getEffectivePower();

        if (in_array($attackingGroup->getAttackType(), $this->getWeaknesses())) {
            $expectedDamage *= 2;
        }

        return $expectedDamage;
    }

    public function receiveDamage(int $damage): int
    {
        $unitsHit = floor($damage / $this->getUnitHitPoints());
        if ($this->unitCount > $unitsHit) {
            $this->setUnitCount($this->getUnitCount() - $unitsHit);
        } else {
            $this->setUnitCount(0);
        }
        return $unitsHit;
    }


}