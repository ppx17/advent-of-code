import {Day, Vec, Vector} from "../aoc";

export class Day19 extends Day {
    day = (): number => 19;

    part1 = () => new Set<string>(this.knownScanners.map(s => s.beacons.map(b => b.serialize())).flat()).size;
    part2 = () => Math.max(...this.knownScanners.map(a => Math.max(...this.knownScanners.map(b => a.position.manhattan(b.position)))))

    setup = () => {
        let activeScanner: Scanner;
        this.input.forEach(l => {
            if (l.startsWith('---')) {
                if (activeScanner !== undefined) this.allScanners.push(activeScanner);
                activeScanner = new Scanner(l);
            }
            if (l !== "") {
                const v = new Vector(...l.split(',').map(Number));
                if (v.dimensions !== 3) return;
                activeScanner?.beacons.push(v);
            }
        })
        if (activeScanner !== undefined) this.allScanners.push(activeScanner);

        let unknownScanners = [...this.allScanners.slice(1)];
        this.knownScanners = [this.allScanners[0]];

        let found = true;
        while (found && unknownScanners.length > 0) {
            found = false;
            for (const known of this.knownScanners) {
                for (const unknown of unknownScanners) {
                    let offset = this.findOffset(known, unknown);

                    if (offset === undefined) continue;

                    this.knownScanners.push(unknown.applyOffset(offset));
                    unknownScanners = unknownScanners.filter(s => s !== unknown);
                    found = true;
                }
            }
        }
    }

    private findOffset = (known: Scanner, unknown: Scanner): Offset | undefined => {
        const target = 12;
        for (const rotation of QuickRotation.all()) {
            const offsets = new Map<string, number>();
            let high = 0;
            let todo = known.beacons.length;

            for (const beaconA of known.beacons) {
                for (const beaconB of unknown.beacons) {
                    const offset = beaconA.sub(rotation.apply(beaconB));

                    const k = offset.serialize();
                    const c = (offsets.get(k) ?? 0) + 1;
                    if (c === target) return {rotation, offset};

                    offsets.set(k, c);
                    high = Math.max(high, c);
                }

                if(--todo < target - high) break;
            }
        }

        return undefined;
    };

    private knownScanners: Scanner[];
    private allScanners: Scanner[] = [];
}

type Offset = {
    offset: Vec;
    rotation: Rotation
}

class Scanner {
    constructor(public name: string, public beacons: Vec[] = [], public position: Vector = Vector.zero(3)) {
    }

    applyOffset = (offset: Offset): Scanner => {
        this.position = offset.offset;
        this.beacons = this.beacons.map(b => offset.rotation.apply(b).add(offset.offset));
        return this;
    }
}

interface Rotation {
    apply(vec: Vec): Vec;
}

class SteppedRotation {
    public static* all(): Generator<Rotation> {
        for (let y = 0; y < 4; y++)
            for (let x = 0; x < 4; x++)
                yield new SteppedRotation(x, y, 0);

        for (let z of [1, 3])
            for (let x = 0; x < 4; x++)
                yield new SteppedRotation(x, 0, z);
    }

    constructor(public x: number, public y: number, public z: number) {
    }

    apply(vec: Vec): Vec {
        for (let z = 0; z < this.z; z++) vec = vec.rotateAroundZ();
        for (let y = 0; y < this.y; y++) vec = vec.rotateAroundY();
        for (let x = 0; x < this.x; x++) vec = vec.rotateAroundX();

        return vec;
    }
}

class QuickRotation implements Rotation {
    private static rotations: Rotation[];
    public static all(): Rotation[] {
        return this.rotations ??= [
            new QuickRotation(v => v),
            new QuickRotation(v => new Vector(v.x, -v.y, -v.z)),
            new QuickRotation(v => new Vector(v.x, -v.z, v.y)),
            new QuickRotation(v => new Vector(v.x, v.z, -v.y)),

            new QuickRotation(v => new Vector(-v.x, v.y, -v.z)),
            new QuickRotation(v => new Vector(-v.x, -v.y, v.z)),
            new QuickRotation(v => new Vector(-v.x, v.z, v.y)),
            new QuickRotation(v => new Vector(-v.x, -v.z, -v.y)),

            new QuickRotation(v => new Vector(v.y, v.x, -v.z)),
            new QuickRotation(v => new Vector(v.y, -v.x, v.z)),
            new QuickRotation(v => new Vector(v.y, v.z, v.x)),
            new QuickRotation(v => new Vector(v.y, -v.z, -v.x)),

            new QuickRotation(v => new Vector(-v.y, v.x, v.z)),
            new QuickRotation(v => new Vector(-v.y, -v.x, -v.z)),
            new QuickRotation(v => new Vector(-v.y, v.z, -v.x)),
            new QuickRotation(v => new Vector(-v.y, -v.z, v.x)),

            new QuickRotation(v => new Vector(v.z, v.x, v.y)),
            new QuickRotation(v => new Vector(v.z, -v.x, -v.y)),
            new QuickRotation(v => new Vector(v.z, v.y, -v.x)),
            new QuickRotation(v => new Vector(v.z, -v.y, v.x)),

            new QuickRotation(v => new Vector(-v.z, v.x, -v.y)),
            new QuickRotation(v => new Vector(-v.z, -v.x, v.y)),
            new QuickRotation(v => new Vector(-v.z, v.y, v.x)),
            new QuickRotation(v => new Vector(-v.z, -v.y, -v.x)),
        ];
    }

    constructor(public apply: (vec: Vec) => Vec) {}
}