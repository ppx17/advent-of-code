import {Day, sum} from "../aoc";

export class Day10 extends Day {
    day = (): number => 10;

    private brackets: Map<string, string> = new Map([["{", "}"], ["(", ")"], ["<", ">"], ["[", "]"]]);
    private lines: string[][];

    part1 = () => this.process(c => ({")": 3, "]": 57, "}": 1197, ">": 25137})[c] ?? 0).reduce(sum)

    part2 = () => Day10.median(this.process(
        _ => 0,
        queue => queue.reverse().reduce((acc, c) => acc * 5 + ["(", "[", "{", "<"].indexOf(c) + 1, 0)
    ).filter(s => s > 0));

    setup = () => this.lines = this.input.map(l => l.split(''));

    private process = (corrupted: (c: string) => number, incomplete?: (queue: string[]) => number): number[] =>
        this.lines.map(line => {
            const queue: string[] = [];

            for (const c of line) {
                if (this.brackets.has(c)) queue.push(c);
                else if (this.brackets.get(queue.pop()) !== c) return corrupted(c);
            }

            return incomplete === undefined ? 0 : incomplete(queue);
        });

    private static median = (numbers: number[]): number =>
        numbers.sort((a, b) => a - b)[Math.floor(numbers.length / 2)];
}