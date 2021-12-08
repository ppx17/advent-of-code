import {Day} from "../aoc";
import {sum} from "../aoc";

export class Day1 extends Day {
    day = (): number => 1;
    private depth: number[];

    part1 = () => this.increases(1);
    part2 = () => this.increases(3);
    setup = () => this.depth = this.input.map(Number);

    private increases = (ws = 1) => this.depth
        .filter(( cur, i, l) => i !== 0 && i <= l.length - ws && this.window(i, ws) > this.window(i - 1, ws))
        .length;

    private window = (i: number, size: number): number => this.depth.slice(i, i + size).reduce(sum);
}