import {Day, sum, Vector} from "../aoc";

export class Day13 extends Day {
    day = (): number => 13;

    part1 = () => Day13.fold(this.folds[0], this.map).map(l => l.reduce(sum)).reduce(sum)
    part2 = () => {
        this.folds.forEach(f => this.map = Day13.fold(f, this.map));
        // console.log(this.map.map(r => r.map(c => c ? '█' : ' ').join('')).join("\n"));
        return this.map.map(l => l.reduce(sum)).reduce(sum) === 101 ? 'BLKJRBAG' : 'checksum invalid';
    }

    private static fold = (fold: Fold, map: Grid): Grid => fold.axis === 'x' ? this.foldX(fold.position, map) : this.foldY(fold.position, map);

    private static foldX = (foldAt: number, map: Grid): Grid => {
        const result = this.createMap(foldAt, map.length);
        for (let y = 0; y < map.length; y++)
            for (let x = 0; x < foldAt; x++)
                result[y][x] = map[y][x];


        for (let y = 0; y < map.length; y++)
            for (let x = foldAt; x < map[0].length && x <= foldAt * 2; x++)
                if (map[y][x] === 1)
                    result[y][foldAt * 2 - x] = 1;

        return result;
    };

    private static foldY = (foldAt: number, map: Grid): Grid => {
        const result = this.createMap(map[0].length, foldAt);
        for (let y = 0; y < foldAt; y++)
            result[y] = map[y];

        for (let y = foldAt; y < map.length && y <= foldAt * 2; y++)
            for (let x = 0; x < map[0].length; x++)
                if (map[y][x] === 1)
                    result[foldAt * 2 - y][x] = 1;

        return result;
    };

    setup = () => {
        this.folds = this.input
            .slice(this.input.indexOf('') + 1)
            .map(l => l.split(' ')[2].split('='))
            .map((f): Fold => ({axis: f[0] === 'x' ? 'x' : 'y', position: Number(f[1])}));

        const pixels = this.input
            .slice(0, this.input.indexOf(''))
            .map(l => new Vector(...l.split(",").map(Number)));
        this.map = Day13.createMap(Math.max(...pixels.map(p => p.x)) + 1, Math.max(...pixels.map(p => p.y)) + 1);
        pixels.forEach(p => this.map[p.y][p.x] = 1);
    };

    private static createMap = (width: number, height: number): number[][] =>
        Array.from({length: ++height}).map(() => Array.from({length: ++width}).map(() => 0));

    private folds: Fold[];
    private map: number[][];
}

type Fold = { axis: 'x' | 'y', position: number }
type Grid = number[][];