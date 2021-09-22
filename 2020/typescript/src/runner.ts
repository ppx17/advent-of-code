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

        if (instance === null) {
            console.warn(`Day ${day} not found.`);
            return null;
        }

        return instance;
    }
}

export class Judge {
    static judge(day: Day): Result {
        day.setup();
        return new Result(
            day,
            this.judgePart(day.day(), 1, day.part1()),
            this.judgePart(day.day(), 2, day.part2()),
        )
    }

    private static judgePart(dayNumber: number, part: number, result: string): PartResult {
        return new PartResult(
            Tools.expected(dayNumber, part),
            result,
        );
    }
}

export class Result {
    constructor(public day: Day, public part1: PartResult, public part2: PartResult) {
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
}

export class PartResult {
    constructor(public expected: null | string, public answer: string) {
    }

    valid(): ValidationResult {
        if (this.expected === null) return ValidationResult.Unknown;
        return this.expected.trim() === this.answer.trim() ? ValidationResult.Valid : ValidationResult.Invalid;
    }
}

export enum ValidationResult {
    Unknown,
    Valid,
    Invalid
}