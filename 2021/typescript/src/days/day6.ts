import {Day, sum} from "../aoc";

export class Day6 extends Day {
    day = (): number => 6;

    part1 = () => this.cycle(80);
    part2 = () => this.cycle(256);
    private fish: number[];

    cycle = (days: number) => {
        const f = [...this.fish];
        for(let d = 0; d < days; d++) {
            const b = f[0];
            for(let i = 0; i < 8; i++) {
                f[i] = f[i + 1];
            }
            f[8] = b;
            f[6] += b;
        }
        return f.reduce(sum);
    };

    setup = () => {
        this.fish = Array.from({length: 9}).map(() => 0);
        this.input[0].split(',').map(Number).forEach(f => this.fish[f]++);
    };
}