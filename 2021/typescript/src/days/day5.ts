import {Day, Vector} from "../aoc";

export class Day5 extends Day {
    day = (): number => 5;

    part1 = (): string => this.countOverlap(this.lines.filter(l => l.isStraight()));
    part2 = (): string => this.countOverlap(this.lines);

    private countOverlap(lines: Line[]): string {
        const counts = new Map<string, number>();

        lines.forEach(line => Array.from(line.vectors()).forEach(v => counts.set(v, (counts.get(v) ?? 0) + 1)));

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
    constructor(private a: Vector, private b: Vector) {
    }

    public isStraight = (): boolean => this.a.x === this.b.x || this.a.y === this.b.y;

    public* vectors(): Generator<string> {
        const step = new Vector(Math.sign(this.b.x - this.a.x), Math.sign(this.b.y - this.a.y))
        let pos = this.a;

        yield pos.serialize();
        while (!pos.is(this.b)) {
            pos = pos.add(step);
            yield pos.serialize();
        }
    }
}
