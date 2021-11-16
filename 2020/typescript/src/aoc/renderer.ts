import {PartResult, Result, Time, ValidationResult} from "./runner";
import {table, TableUserConfig} from "table";
import {Day} from "./day";

export class Renderer {
    private static readonly MAX_DAYS = 25;

    static single(result: Result | null): string {
        if (result === null) {
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
                content: `Day ${result.dayNumber}`
            }
        })
    }

    static multiple(results: Result[]): string {
        const config: TableUserConfig = {
            drawHorizontalLine: (line) => [0, 1, this.MAX_DAYS + 1, this.MAX_DAYS + 2].indexOf(line) !== -1
        }

        return table([
            ['Day', '', 'Part 1', 'Part 2', 'Setup', 'P1', 'P2', 'Total'],
            ...this.resultLines(results),
            this.totalLine(results)
        ], config);
    }

    private static resultLines = function*(results: Result[]): Generator<string[]> {
        const resultMap = new Map<number, Result>();
        results.forEach(r => resultMap.set(r.dayNumber, r));

        for (let day = 1; day <= this.MAX_DAYS; day++) {
            yield this.line(day, resultMap.get(day));
        }
    }

    private static line(dayNumber: number, result: Result | undefined): string[] {
        return result === undefined ? this.missingDayLine(dayNumber) : this.resultLine(result);
    }

    private static missingDayLine(dayNumber: number): string[] {
        return [dayNumber.toString(), ' - ', '', '', '', '', '', ''];
    }

    private static resultLine(result: Result): string[] {
        return [
            result.dayNumber.toString(),
            ` ${this.icon(result.valid())} `,
            `${this.icon(result.part1.valid())} ${this.partResult(result.part1)}`,
            `${this.icon(result.part2.valid())} ${this.partResult(result.part2)}`,
            result.setupTime.toString(),
            result.part1.runtime.toString(),
            result.part2.runtime.toString(),
            result.totalTime().toString(),
        ];
    }

    private static totalLine(result: Result[]): string[] {
        return [
            '',
            '',
            '',
            'Total',
            this.sumTime(r => r.setupTime, result),
            this.sumTime(r => r.part1.runtime, result),
            this.sumTime(r => r.part2.runtime, result),
            this.sumTime(r => r.totalTime(), result),
        ];
    }

    private static sumTime(selector: (r: Result) => Time, results: Result[]): string {
        return (new Time(results.map(selector).map(t => t.timeUs).reduce((a, b) => a + b))).toString();
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
                return '\x1b[31mX\x1b[0m';
            case ValidationResult.Unknown:
                return '\x1b[36m❔\x1b[0m';
        }
    }
}