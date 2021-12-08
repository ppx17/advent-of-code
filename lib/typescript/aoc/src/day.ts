import {Tools} from "./tools";

export abstract class Day {
    abstract day(): number;

    abstract part1(): string | number;

    abstract part2(): string | number;

    constructor(protected input: string[] | undefined = undefined) {
    }

    initializeDay() {
        this.input ??= Tools.input(this.day());
        this.setup();

        return this;
    }

    protected setup() {
    }
}