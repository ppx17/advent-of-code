<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day4 extends AbstractDay
{
    private const REQUIRED_FIELDS = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'];
    private const VALID_EYE_COLORS = ['amb', 'blu', 'brn', 'gry', 'grn', 'hzl', 'oth'];

    private Collection $passports;
    private Collection $required;

    public function dayNumber(): int
    {
        return 4;
    }

    public function setUp(): void
    {
        $this->required = collect(self::REQUIRED_FIELDS);
        $this->passports = collect(preg_split("#\n\n#", $this->getInput()))
            ->map(fn($passport) => preg_split("#[\n\s]#", $passport))
            ->map(fn($fields) => collect($fields)
                ->mapWithKeys(function ($field) {
                    [$key, $val] = explode(':', $field, 2);
                    return [$key => $val];
                }));
    }

    public function part1(): string
    {
        return (string)$this->validPassports()
            ->count();
    }

    public function part2(): string
    {
        return (string)$this->validPassports()
            ->filter(fn(Collection $passport) => $this->validYear($passport, 'byr', 1920, 2002))
            ->filter(fn(Collection $passport) => $this->validYear($passport, 'iyr', 2010, 2020))
            ->filter(fn(Collection $passport) => $this->validYear($passport, 'eyr', 2020, 2030))
            ->filter(fn(Collection $passport) => $this->validHeight($passport))
            ->filter(fn(Collection $passport) => preg_match('#^\#[0-9a-f]{6}$#', $passport->get('hcl')))
            ->filter(fn(Collection $passport) => in_array($passport->get('ecl'), self::VALID_EYE_COLORS))
            ->filter(fn(Collection $passport) => preg_match('#^[0-9]{9}$#', $passport->get('pid')))
            ->count();
    }

    private function validPassports(): Collection
    {
        return $this->passports
            ->filter(fn(Collection $passport) => $this->required->first(fn($key) => !$passport->has($key)) === null);
    }

    private function validYear(Collection $passport, string $field, int $min, int $max): bool
    {
        $value = $passport->get($field);
        return is_numeric($value) && $value >= $min && $value <= $max;
    }

    private function validHeight(Collection $passport): bool
    {
        return preg_match(
                '#^(?<len>[0-9]{2,3})(?<unit>in|cm)$#',
                $passport->get('hgt', ''),
                $m
            )
            && (
                ($m['unit'] === 'cm' && $m['len'] >= 150 && $m['len'] <= 193) ||
                ($m['unit'] === 'in' && $m['len'] >= 59 && $m['len'] <= 76)
            );
    }
}
