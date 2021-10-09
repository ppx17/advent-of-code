import {PartResult, Result, ValidationResult} from "./runner";
import {table} from "table";
import {Day} from "./days/day";

export class Renderer {
    static single(result: Result | null): string {
        if(result === null) {
            return 'No result found.';
        }

        return table([
            ['Part', '', 'Expected', 'Result', 'Time'],
            ['Setup', '', '', '', result.setupTime.toString()],
            [1, this.icon(result.part1.valid()), result.part1.expected, result.part1.answer, result.part1.runtime.toString()],
            [2, this.icon(result.part2.valid()), result.part2.expected, result.part2.answer, result.part2.runtime.toString()],
                ['Total', '', '', '', result.totalTime().toString()],
        ], {
            header: {
                alignment: 'center',
                content: `Day ${result.day.day()}`
            }
        })
    }

    static multiple(result: Result[]): string {
        const data = [
            ['Day', '', 'Part 1', 'Part 2','Setup', 'P1', 'P2', 'Total'],
        ];

        result.forEach(r => data.push([
            r.day.day().toString(),
            this.icon(r.valid()),
            `${this.icon(r.part1.valid())} ${this.partResult(r.part1)}`,
            `${this.icon(r.part2.valid())} ${this.partResult(r.part2)}`,
            r.setupTime.toString(),
            r.part1.runtime.toString(),
            r.part2.runtime.toString(),
            r.totalTime().toString(),
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