import {Day, sum, Vector} from "../aoc";

export class Day11 extends Day {
    day = (): number => 11;

    private neighbors = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1],].map(v => new Vector(...v))
    private corner = new Vector(9, 9);
    private octopi: number[][];

    part1 = (): number => Array.from({length: 100})
        .reduce<number>((acc: number, _) => acc + this.step(), 0);

    part2 = () => {
        this.setup();
        for (let step = 1; true; step++) {
            this.step();
            if (this.octopi.map(r => r.reduce(sum)).reduce(sum) === 0) return step;
        }
    }

    setup = () => this.octopi = this.input.map(l => l.split('').map(Number));

    private step = (): number => {
        this.octopi.forEach((r, y) => {
            r.forEach((o, x) => {
                this.octopi[y][x]++;
            });
        });

        const hasFlashed = new Set<string>();

        this.octopi.forEach((r, y) => {
            r.forEach((_, x) => {
                if (this.octopi[y][x] > 9) {
                    this.flash(new Vector(x, y), hasFlashed);
                }
            });
        });

        this.octopi.forEach((r, y) => {
            r.forEach((_, x) => {
                if (this.octopi[y][x] > 9) this.octopi[y][x] = 0;
            });
        });

        return hasFlashed.size;
    };

    private flash = (pos: Vector, hasFlashed: Set<string>) => {
        if (hasFlashed.has(pos.serialize())) return;
        hasFlashed.add(pos.serialize());

        this.neighbors.map(n => n.add(pos)).filter(v => v.within(this.corner)).forEach(v => {
            this.octopi[v.y][v.x]++;
            if (this.octopi[v.y][v.x] > 9) {
                this.flash(v, hasFlashed);
            }
        });
    }
}