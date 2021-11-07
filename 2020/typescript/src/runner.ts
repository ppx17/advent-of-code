import {Day} from "./days/day";
import {Tools} from "./tools";


export class Runner {
    private days: Day[] = [];

    register = (day: Day) => this.days.push(day);

    all = (): Result[] => this.days
        .sort((a, b) => a.day() - b.day())
        .map(this.day)
        .filter(r => r !== null);

    day = (day: Day | number): Result | null => {
        if (typeof day === "number") {
            day = this.dayFromNumber(day);
            if (day === null) return null;
        }

        return Judge.judge(day);
    }

    private dayFromNumber(day: number): Day | null {
        const instance = this.days.find(d => d.day() === day);

        if (instance === undefined) {
            console.warn(`Day ${day} not found.`);
            return null;
        }

        return instance;
    }
}

export class Judge {
    static judge(day: Day): Result {

        const startNs = process.hrtime.bigint();
        day.setup();
        const runtimeNs = process.hrtime.bigint() - startNs;

        return new Result(
            day,
            this.judgePart(day.day(), 1, () => day.part1()),
            this.judgePart(day.day(), 2, () => day.part2()),
            new Time(runtimeNs)
        )
    }

    private static judgePart(dayNumber: number, part: number, payload): PartResult {

        const startNs = process.hrtime.bigint();
        const result = payload();
        const runtimeNs = process.hrtime.bigint() - startNs;

        return new PartResult(
            Tools.expected(dayNumber, part),
            result,
            new Time(runtimeNs)
        );
    }
}

export class Result {
    constructor(public day: Day, public part1: PartResult, public part2: PartResult, public setupTime: Time) {
    }

    valid(): ValidationResult {
        const results: ValidationResult[] = [this.part1.valid(), this.part2.valid()];

        for (const has of [ValidationResult.Unknown, ValidationResult.Invalid]) {
            if (results.indexOf(has) !== -1) {
                return has;
            }
        }

        return ValidationResult.Valid;
    }

    totalTime = (): Time => new Time(this.part1.runtime.time + this.part2.runtime.time + this.setupTime.time);
}

export class PartResult {
    constructor(public expected: null | string, public answer: string, public runtime: Time) {
    }

    valid(): ValidationResult {
        if (this.expected === null) return ValidationResult.Unknown;
        return this.expected.trim() === this.answer.trim() ? ValidationResult.Valid : ValidationResult.Invalid;
    }
}

export class Time {
    constructor(public time: bigint) {
    }

    public toString() {
        if(this.time < 1000n) {
            return `${this.time} ns`;
        }
        if(this.time < 1000_000n) {
            return `${this.time / 1000n} µs`;
        }
        if(this.time < 1_000_000_000n) {
            return `${this.time / 1000_000n} ms`;
        }

        if(this.time < 10_000_000_000n) {
            return `\x1b[31m${this.time / 1000_000n} ms\x1b[0m`;
        }

        return `\x1b[31m${this.time / 1000_000_000n} s\x1b[0m`;
    }
}

export enum ValidationResult {
    Unknown,
    Valid,
    Invalid
}