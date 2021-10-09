import {Tools} from "../tools";

export abstract class Day {
    abstract day(): number;

    abstract part1(): string;

    abstract part2(): string;

    constructor(protected input: string[] | undefined = undefined) {
    }

    setup() {
        this.input ??= Tools.input(this.day());
    }
}