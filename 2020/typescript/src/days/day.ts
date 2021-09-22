import {Tools} from "../tools";

export abstract class Day {
    protected input: string[] = [];

    abstract day(): number;

    abstract part1(): string;

    abstract part2(): string;

    setup() {
        this.input = Tools.input(this.day());
    }
}