import {Day} from "../aoc";

export class Day4 extends Day {
    private passports: Map<string, string>[];

    private readonly required = new Map<string, (i: string) => boolean>([
        ['byr', (i) => Day4.year(i, 1920, 2002)],
        ['iyr',  (i) => Day4.year(i, 2010, 2020)],
        ['eyr',  (i) => Day4.year(i, 2020, 2030)],
        ['hgt', (i) => Day4.height(i)],
        ['hcl', (i) => i.match(/^#[a-f0-9]{6}$/) !== null],
        ['ecl', (i) => i.match(/^(amb|blu|brn|gry|grn|hzl|oth)$/) !== null],
        ['pid', (i) => i.match(/^[0-9]{9}$/) !== null],
    ]);

    day = (): number => 4;

    part1 = (): string => this.passports.filter(p => this.hasRequiredFields(p)).length.toString();

    part2 = (): string => this.passports.filter(p => this.hasValidFields(p)).length.toString();

    setup = () =>
        this.passports = this.input
            .join("\n")
            .split("\n\n")
            .map(s => s.split(/\s+/).map(p => p.split(':', 2)))
            .map(document => {
                const map = new Map<string, string>();
                document.forEach(field => map.set(field[0], field[1]));
                return map;
            });

    private hasRequiredFields(passport: Map<string, string>): boolean {
        for (const field of this.required.keys()) {
            if (!passport.has(field)) return false;
        }
        return true;
    }

    private hasValidFields(passport: Map<string, string>): boolean {
        for(const [field, validator] of this.required.entries()) {
            if(!passport.has(field) || !validator(passport.get(field))) return false;
        }
        return true;
    }

    private static year(i: string, min: number, max: number): boolean {
        const n = parseInt(i);
        return !isNaN(n) && n >= min && n <= max;
    }

    private static height(i: string): boolean {
        const m = i.match(/^(?<len>[0-9]{2,3})(?<unit>in|cm)$/);
        if(m === null) return false;
        const [unit, len] = [m.groups.unit, parseInt(m.groups.len)];
        return (unit === 'cm' && len >= 150 && len <= 193) || (unit === 'in' && len >= 59 && len <= 76);
    }
}