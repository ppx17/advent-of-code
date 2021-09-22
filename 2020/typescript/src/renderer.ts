import {PartResult, Result, ValidationResult} from "./runner";
import {table} from "table";

export class Renderer {
    static single(result: Result): string {
        return table([
            ['Part', '', 'Expected', 'Result'],
            [1, this.icon(result.part1.valid()), result.part1.expected, result.part1.answer],
            [2, this.icon(result.part2.valid()), result.part2.expected, result.part2.answer]
        ], {
            header: {
                alignment: 'center',
                content: `Day ${result.day.day()}`
            }
        })
    }

    static multiple(result: Result[]): string {
        const data = [
            ['Day', '', 'Part 1', 'Part 2'],
        ];

        result.forEach(r => data.push([
            r.day.day().toString(),
            this.icon(r.valid()),
            `${this.icon(r.part1.valid())} ${this.partResult(r.part1)}`,
            `${this.icon(r.part2.valid())} ${this.partResult(r.part2)}`,
        ]));

        return table(data);
    }

    private static partResult(result: PartResult): string {
        switch (result.valid()) {
            case ValidationResult.Unknown:
            case ValidationResult.Valid:
                return result.answer;
            case ValidationResult.Invalid:
                return `"${result.answer}" (expected: "${result.expected}")`
        }
    }

    private static icon(result: ValidationResult) {
        switch (result) {
            case ValidationResult.Valid:
                return '✔';
            case ValidationResult.Invalid:
                return '❌';
            case ValidationResult.Unknown:
                return '❔';
        }
    }
}