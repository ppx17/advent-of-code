import {Day} from "../aoc/day";

export class Day2 extends Day {
    day = (): number => 2;

    part1 = (): string => this.countWith((passwd, char, min, max) =>
        Day2.charCount(char, passwd) >= min && Day2.charCount(char, passwd) <= max
    );

    part2 = (): string => this.countWith((pass, c, a, b) =>
        (pass[a - 1] === c || pass[b - 1] === c) && !(pass[a - 1] === c && pass[b - 1] === c));

    private countWith(evaluate: { (passwd: string, char: string, min: number, max: number): boolean }): string {
        return this.input
            .map((l) => l.match(/^(?<min>\d+)-(?<max>\d+) (?<char>\w): (?<passwd>\w+)$/))
            .filter(match => (match === null || match.groups === undefined)
                ? false
                : evaluate(
                    match.groups['passwd'],
                    match.groups['char'],
                    parseInt(match.groups['min']),
                    parseInt(match.groups['max'])
                )
            )
            .length
            .toString();
    }

    private static charCount(char: string, subject: string): number {
        let result = 0;
        for (let c of subject) {
            result += Number(c === char);
        }

        return result
    }
}