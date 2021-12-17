import {Day, Vector} from "../aoc";

export class Day17 extends Day {
    day = (): number => 17;

    part1 = () => this.launcher.getLegitProbes().sort((a, b) => b.maxHeight - a.maxHeight)[0].maxHeight;
    part2 = () => this.launcher.getLegitProbes().length;

    setup = () => {
        const coords = this.input[0].split(': x=')[1].split(', y=').map(c => c.split('..').map(Number));

        const min = new Vector(Math.min(...coords[0]), Math.max(...coords[1]));
        const max = new Vector(Math.max(...coords[0]), Math.min(...coords[1]));

        this.launcher = new ProbeLauncher(min, max, -min.x, min.x, max.x + 1);
    };

    private launcher: ProbeLauncher;
}

class Probe {
    position: Vector = new Vector(0, 0);
    maxHeight: number = 0;

    constructor(public velocity: Vector) {
    }

    public step() {
        this.position = this.position.add(this.velocity);
        this.velocity = new Vector(this.velocity.x - Math.sign(this.velocity.x), this.velocity.y - 1);
        this.maxHeight = Math.max(this.maxHeight, this.position.y);
    }
}

class ProbeLauncher {
    private legitProbes: Probe[];

    constructor(private min: Vector, private max: Vector, private minY: number, private maxY: number, private maxX: number) {
    }

    public getLegitProbes(): Probe[] {
        if (this.legitProbes === undefined) this.launchProbes();
        return this.legitProbes;
    }

    private launchProbes(): void {
        this.legitProbes = [];
        for (let y = this.minY; y < this.maxY; y++)
            for (let x = 0; x < this.maxX; x++)
                this.launchProbe(new Vector(x, y));
    }

    private launchProbe(velocity: Vector): void {
        const probe = new Probe(velocity);

        while (
            (   // Valid X motion
                (probe.position.x < this.min.x && probe.velocity.x > 0) // probe is moving horizontally in the right direction
                || (probe.position.x >= this.min.x && probe.position.x <= this.max.x) // probe is in the correct X coords
            ) && ( // Valid Y motion
                (probe.position.y > this.max.y) // probe not yet dropped below the target zone
            )
            ) {
            probe.step();

            if (probe.position.between(this.min, this.max)) {
                this.legitProbes.push(probe);
                break;
            }
        }
    }
}