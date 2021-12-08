import {Day, sum} from "../aoc";

export class Day7 extends Day {
    day = (): number => 7;

    part1 = () => {
        const target = Day7.median(this.pos);
        return this.pos.map(p => Math.abs(p - target)).reduce(sum);
    }

    part2 = () => {
        const min = Math.min(...this.pos);
        return Math.min(...Array.from({length: Math.max(...this.pos) - min})
            .map((v,i) => this.pos.map(p => Day7.triangle(Math.abs(p - i+min))).reduce(sum)));
    }

    private static triangle = (n: number): number =>
        Math.floor(n * (n + 1) / 2);

    private static median = (numbers: number[]): number =>
        numbers.sort((a, b) => a - b)[Math.floor(numbers.length / 2)];

    setup = () => this.pos = this.input[0].split(',').map(Number);

    private pos: number[];
}