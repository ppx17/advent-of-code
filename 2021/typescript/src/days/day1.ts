import {Day} from "../aoc";
import {sum} from "../aoc";

export class Day1 extends Day {
    day = (): number => 1;
    private depth: number[];

    part1 = (): string => this.increases(1);
    part2 = (): string => this.increases(3);

    private increases = (ws = 1): string => this.depth.filter(( cur, i, l) => i !== 0 && i <= l.length - ws && this.window(i, ws) > this.window(i - 1, ws))
        .length
        .toString();

    private window = (i: number, size: number): number => this.depth.slice(i, i + size).reduce(sum);

    setup() {
        super.setup();
        this.depth = this.input.map(Number);
    }
}