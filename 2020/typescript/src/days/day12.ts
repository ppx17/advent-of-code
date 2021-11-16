import {Day} from "../aoc/day";
import {Vector} from "./helpers";

export class Day12 extends Day {
    day = (): number => 12;

    part1 = (): string => (new MovingBoat(Vector.east())).sail(this.input).distanceFromOrigin();
    part2 = (): string => (new WaypointBoat(new Vector(10, -1))).sail(this.input).distanceFromOrigin();
}

abstract class Boat {
    protected readonly directions = new Map<string, Vector>([
        ['N', Vector.north()],
        ['E', Vector.east()],
        ['S', Vector.south()],
        ['W', Vector.west()],
    ]);

    constructor(protected waypoint: Vector, public position: Vector = Vector.zero()) {
    }

    sail(commands: string[]): Boat {
        commands.forEach(cmd => this.move(cmd));
        return this;
    }

    distanceFromOrigin = (): string => this.position.manhattan(Vector.zero()).toString();

    protected abstract moveToWind(direction: string, distance: number);

    protected moveVector = (v: Vector, dir: string, dist: number): Vector => v.add(this.directions.get(dir).times(dist));

    private move(cmd: string): void {
        const distance = Number.parseInt(cmd.substring(1));
        const action = cmd[0];

        if (action === 'F') {
            this.position = this.position.add(this.waypoint.times(distance));
        } else if (this.directions.has(action)) {
            this.moveToWind(action, distance);
        } else {
            this.rotateWaypoint(action, distance);
        }
    }

    private rotateWaypoint(dir: string, dist: number): void {
        let steps = dist % 360 / 90;
        if (dir === 'L') steps = (4 - steps);
        for (let i = 0; i < steps; i++) this.waypoint = this.rotateRight(this.waypoint);
    }

    private rotateRight = (vector: Vector): Vector => new Vector(-vector.y, vector.x);
}

class MovingBoat extends Boat {
    protected moveToWind = (dir: string, dist: number) => this.position = this.moveVector(this.position, dir, dist);
}

class WaypointBoat extends Boat {
    protected moveToWind = (dir: string, dist: number) => this.waypoint = this.moveVector(this.waypoint, dir, dist);
}
