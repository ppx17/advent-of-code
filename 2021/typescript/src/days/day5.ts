import {Day, Vector} from "../aoc";

export class Day5 extends Day {
    day = (): number => 5;

    part1 = (): string => this.countOverlap(this.lines.filter(l => l.isStraight()))
    part2 = (): string => this.countOverlap(this.lines)

    private countOverlap(lines: Line[]): string {
        const counts = new Map<string, number>();

        lines.forEach(line => {
            for (const v of line.vectors()) {
                counts.set(v, (counts.get(v) ?? 0) + 1);
            }
        });

        return Array.from(counts.values()).filter(n => n >= 2).length.toString();
    }

    private lines: Line[];

    setup() {
        super.setup();

        this.lines = this.input
            .map(l => l.split(' -> ')
                .map(point => point.split(',').map(Number))
                .map(v => new Vector(...v))
            ).map(vectors => new Line(vectors[0], vectors[1]));
    }
}

class Line {
    constructor(public a: Vector, public b: Vector) {
    }

    public isStraight = (): boolean => this.a.x === this.b.x || this.a.y === this.b.y;

    public* vectors(): Generator<string> {
        const dist = Math.max(Math.abs(this.a.x - this.b.x), Math.abs(this.a.y - this.b.y));

        const stepX = Math.floor((this.b.x - this.a.x) / dist);
        const stepY = Math.floor((this.b.y - this.a.y) / dist);

        for (let s = 0; s <= dist; s++) {
            const x = this.a.x === this.b.x ? this.a.x : this.a.x + (stepX * s);
            const y = this.a.y === this.b.y ? this.a.y : this.a.y + (stepY * s);

            yield `${x}:${y}`;
        }
    }
}
