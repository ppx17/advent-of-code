import {Day, Vector} from "../aoc";

export class Day19 extends Day {
    day = (): number => 19;

    part1 = () => new Set<string>(this.fixedScanners.map(s => s.beacons.map(b => b.serialize())).flat()).size;
    part2 = () => Math.max(...this.scannerPositions.map(a => Math.max(...this.scannerPositions.map(b => a.manhattan(b)))))

    setup = () => {
        let activeScanner: Scanner;
        this.input.forEach(l => {
            if (l.startsWith('---')) {
                if (activeScanner !== undefined) this.scanners.push(activeScanner);
                activeScanner = new Scanner(l);
            }
            if (l !== "") {
                const v = new Vector(...l.split(',').map(Number));
                if (v.dimensions !== 3) return;
                activeScanner?.beacons.push(v);
            }
        })
        if (activeScanner !== undefined) this.scanners.push(activeScanner);

        let unknownScanners = [...this.scanners.slice(1)];
        this.fixedScanners = [this.scanners[0]];

        let foundScanner = true;

        while (foundScanner && unknownScanners.length > 0) {
            foundScanner = false;
            for (const scannerA of this.fixedScanners) {
                for (const scannerB of unknownScanners) {
                    let offset = this.findOffset(scannerA, scannerB);

                    if (offset === undefined) continue;

                    this.scannerPositions.push(offset.offset);

                    this.fixedScanners.push(new Scanner(scannerB.name, scannerB.beacons.map(b => offset.rotation.apply(b).add(offset.offset))));
                    unknownScanners = unknownScanners.filter(s => s !== scannerB);
                    foundScanner = true;
                }
            }
        }
    }

    private findOffset = (scannerA: Scanner, scannerB: Scanner): Offset | undefined => {
        for (const rotation of Rotation.all) {
            const offsets = new Map<string, number>();

            for (const beaconA of scannerA.beacons) {

                for (const beaconB of scannerB.beacons) {
                    const rotatedBeaconB = rotation.apply(beaconB);

                    const offset = beaconA.sub(rotatedBeaconB);

                    const k = offset.serialize();
                    const c = (offsets.get(k) ?? 0) + 1;
                    offsets.set(k, c);
                    if (c >= 12) {
                        return {rotation, offset};
                    }
                }
            }
        }

        return undefined;
    };

    private fixedScanners: Scanner[];
    private scannerPositions: Vector[] = [];
    private scanners: Scanner[] = [];
}

type Offset = {
    offset: Vector;
    rotation: Rotation
}

class Scanner {
    constructor(public name: string, public beacons: Vector[] = []) {
    }
}

class Rotation {
    public static all: Rotation[] = [
        new Rotation(0, 0, 0),
        new Rotation(1, 0, 0),
        new Rotation(2, 0, 0),
        new Rotation(3, 0, 0),

        new Rotation(0, 1, 0),
        new Rotation(1, 1, 0),
        new Rotation(2, 1, 0),
        new Rotation(3, 1, 0),

        new Rotation(0, 2, 0),
        new Rotation(1, 2, 0),
        new Rotation(2, 2, 0),
        new Rotation(3, 2, 0),

        new Rotation(0, 3, 0),
        new Rotation(1, 3, 0),
        new Rotation(2, 3, 0),
        new Rotation(3, 3, 0),

        new Rotation(0, 0, 1),
        new Rotation(1, 0, 1),
        new Rotation(2, 0, 1),
        new Rotation(3, 0, 1),

        new Rotation(0, 0, 3),
        new Rotation(1, 0, 3),
        new Rotation(2, 0, 3),
        new Rotation(3, 0, 3),
    ];

    constructor(public x: number, public y: number, public z: number) {
    }

    apply(vec: Vector): Vector {
        for (let z = 0; z < this.z; z++) vec = vec.rotateAroundZ();
        for (let y = 0; y < this.y; y++) vec = vec.rotateAroundY();
        for (let x = 0; x < this.x; x++) vec = vec.rotateAroundX();

        return vec;
    }
}