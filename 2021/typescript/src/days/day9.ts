import {Day, product, sum, Vector} from "../aoc";

export class Day9 extends Day {
    day = (): number => 9;

    part1 = () => this.low.map(v => this.fromGrid(v) + 1).reduce(sum);
    part2 = () =>
        this.low
            .map(start => {
                const seen = new Set<string>();
                const queue = [];
                queue.push(start);
                seen.add(start.serialize());

                while (queue.length > 0) {
                    const curr = queue.pop();
                    this.dirs.map(v => v.add(curr)).forEach(v => {
                        const num = this.fromGrid(v);
                        if (num === -1 || num === 9 || seen.has(v.serialize())) return;
                        queue.push(v);
                        seen.add(v.serialize());
                    });
                }

                return seen.size;
            })
            .sort((a, b) => b - a)
            .slice(0, 3)
            .reduce(product);

    private dirs: Vector[];
    private grid: number[][];
    private low: Vector[];

    setup = () => {
        this.grid = this.input.map(l => l.split('').map(n => Number(n)));
        this.dirs = [Vector.north(), Vector.east(), Vector.south(), Vector.west()];
        this.low = this.lowPoints();
    };

    private fromGrid = (pos: Vector): undefined | number => (this.grid[pos.y] ?? [])[pos.x] ?? -1;

    private lowPoints = (): Vector[] => {
        const result = [];

        this.grid.forEach((r, y) => {
            r.forEach((v, x) => {
                const pos = new Vector(x, y);
                const neighbors = this.dirs.map(v => this.fromGrid(pos.add(v))).filter(n => n !== -1);
                if (neighbors.every(n => n > this.fromGrid(pos))) {
                    result.push(pos);
                }
            });
        });

        return result;
    };
}