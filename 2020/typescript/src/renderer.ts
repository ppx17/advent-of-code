import {PartResult, Result, Time, ValidationResult} from "./runner";
import {table, TableUserConfig} from "table";
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

        result.forEach((r, i) => data.push([
            r.day.day().toString(),
            ` ${this.icon(r.valid())} `,
            `${this.icon(r.part1.valid())} ${this.partResult(r.part1)}`,
            `${this.icon(r.part2.valid())} ${this.partResult(r.part2)}`,
            r.setupTime.toString(),
            r.part1.runtime.toString(),
            r.part2.runtime.toString(),
            r.totalTime().toString(),
        ]));

        data.push([
            '',
            '',
            '',
            'Total',
            this.sumTime(r => r.setupTime, result),
            this.sumTime(r => r.part1.runtime, result),
            this.sumTime(r => r.part2.runtime, result),
            this.sumTime(r => r.totalTime(), result),
        ]);

        const config: TableUserConfig = {
            drawHorizontalLine: (line) => [0, 1, result.length + 1, result.length + 2].indexOf(line) !== -1
        }

        return table(data, config);
    }

    private static sumTime(selector: (r: Result) => Time, results: Result[]): string {
        return (new Time(results.map(selector).map(t => t.time).reduce((a, b) => a + b))).toString();
    }

    private static partResult(result: PartResult): string {
        switch (result.valid()) {
            case ValidationResult.Unknown:
            case ValidationResult.Valid:
                return result.answer;
            case ValidationResult.Invalid:
                return `'${result.answer}' expected: '${result.expected}'`
        }
    }

    private static icon(result: ValidationResult) {
        switch (result) {
            case ValidationResult.Valid:
                return '\x1b[32m✔\x1b[0m';
            case ValidationResult.Invalid:
                return '\x1b[31m❌\x1b[0m';
            case ValidationResult.Unknown:
                return '\x1b[36m❔\x1b[0m';
        }
    }
}