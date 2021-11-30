import {Day} from "../aoc";
import {sum, Vector} from "./helpers";

export class Day24 extends Day {

    day = (): number => 24;

    part1 = (): string => {
        return this.initialFloor().blackTileCount().toString();
    }

    part2 = (days = 100): string => {
        let floor = this.initialFloor();

        for(let day = 1; day <= days; day++) {
            floor = Flipper.flip(floor);
        }

        return floor.blackTileCount().toString();
    }

    private initialFloor(): Floor {
        const floor = new Floor();
        this.input.map(l => Navigator.navigate(l)).forEach(v => floor.toggle(v));
        return floor;
    }

}

const WHITE = false;
const BLACK = true;

type Color = boolean;
type FloorTile = { color: Color, location: Vector };


class Floor {
    public readonly floor = new Map<string, FloorTile>();

    public toggle(location: Vector) {
        this.set(location, !this.get(location));
    }

    public get(location: Vector): boolean {
        return this.floor.get(location.serialize())?.color ?? WHITE;
    }

    public set(location: Vector, status: boolean): void {
        this.floor.set(location.serialize(), {location: location, color: status});
    }

    public has(location: Vector): boolean {
        return this.floor.has(location.serialize());
    }

    public blackTileCount(): number {
        return Array.from(this.floor.values()).map(t => Number(t.color)).reduce(sum);
    }
}

class Flipper {
    public static flip(floor: Floor): Floor {
        const flipped = new Floor();

        floor.floor.forEach((tile) => {
            flipped.set(tile.location, this.newColor(tile, floor));

            Navigator.neighbors(tile.location).forEach((location: Vector) => {
                if(flipped.has(location)) return;

                const tile: FloorTile = {color: floor.get(location), location: location};
                flipped.set(location, this.newColor(tile, floor));
            });
        });

        return flipped;
    }


    private static newColor(tile: FloorTile, original: Floor): Color {
        const neighborCount = Navigator.neighbors(tile.location).map(v => original.get(v)).map(Number).reduce(sum);

        return tile.color === BLACK ? neighborCount === 1 || neighborCount === 2 : neighborCount === 2;
    }
}

class Navigator {
    private static readonly directions = new Map([
        ['e', new Vector(2, 0)],
        ['w', new Vector(-2, 0)],
        ['se', new Vector(1, 1)],
        ['sw', new Vector(-1, 1)],
        ['ne', new Vector(1, -1)],
        ['nw', new Vector(-1, -1)],
    ]);

    private static readonly vectors = Array.from(this.directions.values());

    public static navigate(instructionLine: string): Vector {
        const instructions = instructionLine.split('');
        let location = Vector.zero();
        while (instructions.length > 0) {
            let instruction = instructions.shift();
            if (instruction === 'n' || instruction === 's') {
                instruction += instructions.shift();
            }

            location = location.add(this.directions.get(instruction));
        }

        return location;
    }

    public static neighbors(vector: Vector): Vector[] {
        return this.vectors.map(d => d.add(vector));
    }
}
