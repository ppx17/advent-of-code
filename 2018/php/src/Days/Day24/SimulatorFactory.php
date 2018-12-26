<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 24-12-18
 * Time: 9:32
 */

namespace Ppx17\Aoc2018\Days\Day24;


class SimulatorFactory
{
    public function create(string $data, int $boost = 0): Simulator
    {
        $regex = "#(?<units>\d+) units each with (?<hitpoints>\d+) hit points (\((?<first_class>\w+) to (?<first_list>((\w+(, )?)*))(; (?<second_class>\w+) to (?<second_list>(\w+(, )?)*))?\) )?with an attack that does (?<damagepts>\d+) (?<attacktype>\w+) damage at initiative (?<initiative>\d+)#";
        $parts = explode("Infection:", $data);
        preg_match_all($regex, $parts[0], $immune, PREG_SET_ORDER);
        preg_match_all($regex, $parts[1], $infection, PREG_SET_ORDER);

        $immuneGroups = [];
        $infectionGroups = [];
        $i = 1;
        foreach ($immune as $immuneGroup) {
            $group = $this->groupFromRegexResult($immuneGroup, 'Immune', $i++);
            $group->setAttackDamage($group->getAttackDamage() + $boost);
            $immuneGroups[] = $group;
        }

        $i = 1;
        foreach ($infection as $infectionGroup) {
            $infectionGroups[] = $this->groupFromRegexResult($infectionGroup, 'Infection', $i++);
        }

        return new Simulator($immuneGroups, $infectionGroups);
    }

    private function groupFromRegexResult(array $resultGroup, string $groupType, int $index): Group
    {
        $group = (new Group(sprintf("%s %s", $groupType, $index)))
            ->setUnitCount($resultGroup['units'])
            ->setUnitHitPoints($resultGroup['hitpoints'])
            ->setAttackDamage($resultGroup['damagepts'])
            ->setAttackType($resultGroup['attacktype'])
            ->setInitiative($resultGroup['initiative']);
        if ($resultGroup['first_class'] === 'immune') {
            $group->setImmunities(explode(', ', $resultGroup['first_list']));
        } elseif ($resultGroup['first_class'] === 'weak') {
            $group->setWeaknesses(explode(', ', $resultGroup['first_list']));
        }
        if ($resultGroup['second_class'] === 'immune') {
            $group->setImmunities(explode(', ', $resultGroup['second_list']));
        } elseif ($resultGroup['second_class'] === 'weak') {
            $group->setWeaknesses(explode(', ', $resultGroup['second_list']));
        }
        return $group;
    }
}