<?php

namespace Ppx17\Aoc2018\Days\Day24;


class Simulator
{
    private $immuneGroups;
    private $infectionGroups;

    public function __construct(array $immuneGroups, array $infectionGroups)
    {
        $this->immuneGroups = $immuneGroups;
        $this->infectionGroups = $infectionGroups;
    }

    public function fightToDeath(): int
    {
        while ($this->fightRound());
        return $this->getUnitsLeft();
    }

    public function fightRound(): bool
    {
        $unitsAtStart = $this->getUnitsLeft();
        $this->selectTargets();
        $this->attackTargets();
        if($this->getUnitsLeft() !== $unitsAtStart) {
            return $this->immuneCount() > 0 && $this->infectionCount() > 0;
        }else{
            // No units died, abort fighting
            return false;
        }
    }

    public function immuneCount(): int
    {
        return count($this->immuneGroups);
    }

    public function infectionCount(): int
    {
        return count($this->infectionGroups);
    }

    private function selectTargets()
    {
        $this->selectTargetsForGroup($this->immuneGroups, $this->infectionGroups);
        $this->selectTargetsForGroup($this->infectionGroups, $this->immuneGroups);
    }

    private function selectTargetsForGroup(array $attackers, array $enemies)
    {
        $this->sortGroupsForTargetSelection($attackers);
        $picked = [];
        /** @var Group $attacker */
        foreach ($attackers as $attacker) {
            $bestAttack = 0;
            $this->sortGroupsForTargetSelection($enemies);
            $bestEnemy = null;
            /** @var Group $enemy */
            foreach ($enemies as $enemy) {
                if (in_array($enemy, $picked)) {
                    continue;
                }
                $expectedDamage = $enemy->simulateAttackedBy($attacker);
                if ($expectedDamage > $bestAttack) {
                    $bestEnemy = $enemy;
                    $bestAttack = $expectedDamage;
                }
            }
            $picked[] = $bestEnemy;
            $attacker->setTarget($bestEnemy);
        }
    }

    private function attackTargets()
    {
        $allGroups = array_merge($this->immuneGroups, $this->infectionGroups);
        usort($allGroups, function (Group $a, Group $b) {
            return $b->getEffectiveInitiative() - $a->getEffectiveInitiative();
        });

        /** @var Group $attackingGroup */
        foreach ($allGroups as $attackingGroup) {
            $target = $attackingGroup->getTarget();
            if ($target !== null) {
                $possibleDamage = $target->simulateAttackedBy($attackingGroup);
                $target->receiveDamage($possibleDamage);
            }
        }

        $this->immuneGroups = array_filter($this->immuneGroups, function (Group $group) {
            return $group->getUnitCount() > 0;
        });

        $this->infectionGroups = array_filter($this->infectionGroups, function (Group $group) {
            return $group->getUnitCount() > 0;
        });
    }

    private function sortGroupsForTargetSelection(array &$group)
    {
        usort($group, function (Group $a, Group $b) {
            if ($a->getEffectivePower() === $b->getEffectivePower()) {
                return $b->getEffectiveInitiative() - $a->getEffectiveInitiative();
            }
            return $b->getEffectivePower() - $a->getEffectivePower();
        });
    }

    private function getUnitsLeft(): int
    {
        $sum = 0;
        /** @var Group $group */
        foreach ($this->infectionGroups as $group) {
            //debug("%s had %s units left", $group, $group->getUnitCount());
            $sum += $group->getUnitCount();
        }
        /** @var Group $group */
        foreach ($this->immuneGroups as $group) {
            //debug("%s had %s units left", $group, $group->getUnitCount());
            $sum += $group->getUnitCount();
        }
        return $sum;
    }
}