import {Day, sum} from "../aoc";

export class Day22 extends Day {
    day = (): number => 22;

    part1 = () => {
        const coordinates = new Map<string, number>();

        this.steps.filter(s => s.cube.isInitializationCube()).forEach(step => {
            for (let x = step.cube.minX; x < step.cube.maxX; x++)
                for (let y = step.cube.minY; y < step.cube.maxY; y++)
                    for (let z = step.cube.minZ; z < step.cube.maxZ; z++)
                        coordinates.set(`${x}:${y}:${z}`, Number(step.state));
        });

        return Array.from(coordinates.values()).reduce(sum);
    }
    part2 = () => {
        let cubes: Cube[] = [];
        this.steps.forEach(step => {
            cubes = cubes.flatMap((c) => c.generatePartialCubes(step.cube));
            if (step.state) cubes.push(step.cube);
        });

        return cubes.map((c) => c.volume()).reduce(sum, 0n).toString();
    }

    setup = () => {
        this.steps = this.input
            .map(l => l.match(/(?<s>on|off) x=(?<xmin>-?\d+)\.\.(?<xmax>-?\d+),y=(?<ymin>-?\d+)\.\.(?<ymax>-?\d+),z=(?<zmin>-?\d+)\.\.(?<zmax>-?\d+)/))
            .map(r => r.groups)
            .map((g): Step => ({
                state: g.s === 'on',
                cube: new Cube(Number(g.xmin), Number(g.ymin), Number(g.zmin), Number(g.xmax) + 1, Number(g.ymax) + 1, Number(g.zmax) + 1)
            }));
    };
    private steps: Step[];
}

interface Step {
    state: boolean,
    cube: Cube,
}

class Cube {
    constructor(public minX: number, public minY: number, public minZ: number, public maxX: number, public maxY: number, public maxZ: number) {
    }

    isInitializationCube(): boolean {
        return this.minX >= -50 && this.minY >= -50 && this.minZ >= -50 && this.maxX <= 50 && this.maxY <= 50 && this.maxZ <= 50;
    }

    contains(other: Cube): boolean {
        return this.minX <= other.minX && this.maxX >= other.maxX
            && this.minY <= other.minY && this.maxY >= other.maxY
            && this.minZ <= other.minZ && this.maxZ >= other.maxZ;
    }

    intersects(other: Cube): boolean {
        return this.minX <= other.maxX && this.maxX >= other.minX
            && this.minY <= other.maxY && this.maxY >= other.minY
            && this.minZ <= other.maxZ && this.maxZ >= other.minZ;
    }

    volume(): bigint {
        return BigInt(this.maxX - this.minX) * BigInt(this.maxY - this.minY) * BigInt(this.maxZ - this.minZ);
    }

    generatePartialCubes(other: Cube): Cube[] {
        if (other.contains(this)) return [];
        if (!this.intersects(other)) return [this];

        const xs = this.placeBetween(other, 'X');
        const ys = this.placeBetween(other, 'Y');
        const zs = this.placeBetween(other, 'Z');

        const cubes: Cube[] = [];

        for (let xi = 0; xi < xs.length - 1; xi++)
            for (let yi = 0; yi < ys.length - 1; yi++)
                for (let zi = 0; zi < zs.length - 1; zi++)
                    cubes.push(new Cube(xs[xi], ys[yi], zs[zi], xs[xi+1], ys[yi+1], zs[zi+1]));

        return cubes.filter((c) => !other.contains(c));
    }

    placeBetween(other: Cube, axis: 'X'|'Y'|'Z'): number[] {
        const min = `min${axis}`, max = `max${axis}`, between = [other[min], other[max]].filter(n => this[min] < n && this[max] > n);
        return [this[min], ...between, this[max]];
    }
}
