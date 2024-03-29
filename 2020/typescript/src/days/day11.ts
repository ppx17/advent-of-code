import {Day, Vector} from "../aoc";

export class Day11 extends Day {
    private map: Map;
    private static offsets: Vector[] = [[-1, -1], [-1, 0], [-1, 1], [0, -1], [0, 1], [1, -1], [1, 0], [1, 1]]
        .map(numbers => new Vector(...numbers));

    day = (): number => 11;
    part1 = (): string => this.transform(this.map, Day11.evolvePart1).countOccupied().toString();
    part2 = (): string => this.transform(this.map, Day11.evolvePart2).countOccupied().toString();

    setup = () => this.map = new Map(this.input.map(line => line.split('')));

    private transform(map: Map, evolveSeat: (map: Map, pos: Vector) => string): Map {
        const newMap = map.copy();
        map.forEachTile(pos => newMap.set(pos, evolveSeat(map, pos)));
        return map.equals(newMap) ? newMap : this.transform(newMap, evolveSeat);
    }

    private static evolvePart1(map: Map, position: Vector): string {
        return Day11.evolve(map, position,
            (map, position) => Day11.offsets
                .filter((offset: Vector) => map.isOccupied(position.add(offset)))
                .length,
            4
        );
    }

    private static evolvePart2(map: Map, position: Vector): string {
        return Day11.evolve(map, position,
            (map, position) => Day11.offsets
                .map((offset: Vector) => {
                    let check = position;
                    while (true) {
                        check = check.add(offset);
                        if (!check.within(map.dimensions)) return Map.FLOOR;
                        if (!map.isFloor(check)) return map.get(check);
                    }
                })
                .filter((tile: string) => tile === Map.OCCUPIED)
                .length,
            5
        );
    }

    private static evolve(
        map: Map,
        position: Vector,
        countNeighbors: (map: Map, position: Vector) => number,
        maxNeighbors: number
    ): string {
        if (map.isFloor(position)) return Map.FLOOR;

        const neighbors = countNeighbors(map, position);

        if (map.isOccupied(position) && neighbors >= maxNeighbors) {
            return Map.EMPTY;
        }

        if (map.isEmpty(position) && neighbors === 0) {
            return Map.OCCUPIED;
        }

        return map.get(position);
    }
}

class Map {
    public static readonly FLOOR = '.';
    public static readonly OCCUPIED = '#';
    public static readonly EMPTY = 'L';
    public readonly dimensions: Vector;

    constructor(private map: string[][]) {
        this.dimensions = new Vector(this.map[0].length - 1, this.map.length - 1)
    }

    set = (position: Vector, tile: string) => {
        this.map[position.y][position.x] = tile;
    };

    get = (position: Vector): string => (this.map[position.y] ?? [])[position.x] ?? '';

    copy = (): Map => new Map(this.map.map((line: string[]) => [...line]));

    serialize = (): string => this.map.map(row => row.join('')).join('');

    equals = (other: Map): boolean => this.serialize() === other.serialize();

    countOccupied = (): number => this.map.flat().filter(tile => tile === Map.OCCUPIED).length;

    isFloor = (position: Vector): boolean => this.get(position) === Map.FLOOR;

    isOccupied = (position: Vector): boolean => this.get(position) === Map.OCCUPIED;

    isEmpty = (position: Vector): boolean => this.get(position) === Map.EMPTY;

    forEachTile = (closure: (position: Vector) => void): void => {
        for (let y = 0; y <= this.dimensions.y; y++) {
            for (let x = 0; x <= this.dimensions.x; x++) {
                closure(new Vector(x, y));
            }
        }
    };
}