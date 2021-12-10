import {Day, sum} from "../aoc";

export class Day10 extends Day {
    day = (): number => 10;

    part1 = () => this.process(c => ({")": 3, "]": 57, "}": 1197, ">": 25137})[c] ?? 0).reduce(sum)

    part2 = () => Day10.median(this.process(
            _ => 0,
            queue => queue.reverse().reduce((acc, c) => acc * 5 + {"(": 1, "[": 2, "{": 3, "<": 4}[c], 0)
        ).filter(s => s > 0));

    private process = (corrupted: (c: string) => number, incomplete?: (queue: string[]) => number): number[] =>
        this.lines.map(line => {
            const queue = [];

            for (const c of line) {
                if (["(", "[", "{", "<"].indexOf(c) !== -1) {
                    queue.push(c);
                } else {
                    const toClose = queue.pop();
                    if ({"{": "}", "(": ")", "<": ">", "[": "]"}[toClose] !== c) return corrupted(c);
                }
            }

            return incomplete === undefined ? 0 : incomplete(queue);
        });

    private static median = (numbers: number[]): number =>
        numbers.sort((a, b) => a - b)[Math.floor(numbers.length / 2)];

    setup = () => this.lines = this.input.map(l => l.split(''));

    private lines: string[][];
}