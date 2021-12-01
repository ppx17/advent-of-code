import {Day} from "./day";
import {Tools} from "./tools";
import {Worker as JestWorker} from 'jest-worker';


export class Runner {
    private days: Day[] = [];

    register = (day: any | Day) => Runner.isDay(day) && this.days.push(day);

    all = (): Result[] => this.sortedDays()
        .map(this.day)
        .filter(r => r !== null);

    allAsync = (): Promise<Result[]> => {
        const worker = new JestWorker(require.resolve('./workerThread'), {forkOptions: {silent: true}}) as WorkerThread;

        worker.getStdout().on('data', (data) => console.log(`Worker: ${data}`));

        return Promise.all(this.sortedDays().map((d: Day) => worker.run(d.day())))
            .then((serializedResults): Result[] => {
                worker.end();
                return serializedResults.map(r => ResultDeserializer.result(r));
            });
    }

    day = (day: Day | number): Result | null => {
        if (typeof day === "number") {
            day = this.dayFromNumber(day);
            if (day === null) return null;
        }

        return Judge.judge(day);
    }

    private sortedDays = (): Day[] => this.days.sort((a, b) => a.day() - b.day());

    private dayFromNumber(day: number): Day | null {
        const instance = this.days.find(d => d.day() === day);

        if (instance === undefined) {
            console.warn(`Day ${day} not found.`);
            return null;
        }

        return instance;
    }

    private static isDay(obj: any): obj is Day {
        return "day" in obj && "part1" in obj && "part2" in obj;
    }
}

type WorkerThread = JestWorker & { run: (day: number) => undefined | SerializedResult }

export class Judge {
    static judge(day: Day): Result {

        const startNs = process.hrtime.bigint();
        day.setup();
        const runtimeNs = process.hrtime.bigint() - startNs;

        return new Result(
            day.day(),
            this.judgePart(day.day(), 1, () => day.part1()),
            this.judgePart(day.day(), 2, () => day.part2()),
            Time.fromNsBigint(runtimeNs)
        )
    }

    private static judgePart(dayNumber: number, part: number, payload): PartResult {

        const startNs = process.hrtime.bigint();
        const result = payload();
        const runtimeNs = process.hrtime.bigint() - startNs;

        return new PartResult(
            Tools.expected(dayNumber, part),
            result,
            Time.fromNsBigint(runtimeNs)
        );
    }
}

interface SerializedTime {
    timeUs: number
}

interface SerializedPartResult {
    expected: string | null,
    answer: string,
    runtime: SerializedTime
}

interface SerializedResult {
    dayNumber: number,
    part1: SerializedPartResult,
    part2: SerializedPartResult,
    setupTime: SerializedTime
}

export class ResultDeserializer {
    public static result(serialized: undefined | SerializedResult): undefined | Result {
        return serialized ? new Result(
            serialized.dayNumber,
            this.part(serialized.part1),
            this.part(serialized.part2),
            this.time(serialized.setupTime)
        ) : undefined;
    }

    private static part(serialized: undefined | SerializedPartResult): undefined | PartResult {
        return serialized === undefined ? undefined : new PartResult(serialized.expected, serialized.answer, this.time(serialized.runtime));
    }

    private static time(serialized: SerializedTime): Time {
        return new Time(serialized?.timeUs ?? 0);
    }
}

export class Result {
    constructor(public dayNumber: number, public part1: PartResult, public part2: PartResult, public setupTime: Time) {
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

    totalTime = (): Time => new Time(this.part1.runtime.timeUs + this.part2.runtime.timeUs + this.setupTime.timeUs);
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

    public static fromNsBigint(ns: bigint) {
        return new Time(Number(ns / 1000n));
    }

    constructor(public readonly timeUs: number) {
    }

    public toString() {
        if (this.timeUs < 1000) {
            return `${this.timeUs} µs`;
        }
        if (this.timeUs < 1_000_000) {
            return `${this.timeUs / 1000} ms`;
        }

        if (this.timeUs < 10_000_000) {
            return `\x1b[31m${this.timeUs / 1000} ms\x1b[0m`;
        }

        return `\x1b[31m${this.timeUs / 1000_000} s\x1b[0m`;
    }
}

export enum ValidationResult {
    Unknown,
    Valid,
    Invalid
}