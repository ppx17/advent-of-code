import {Day} from "../aoc/day";
import {product} from "./helpers";

export class Day3 extends Day {
    day = (): number => 3;

    part1 = (): string => this.treeCount({x: 3, y: 1}).toString();
    part2 = (): string => [
        {x: 1, y: 1},
        {x: 3, y: 1},
        {x: 5, y: 1},
        {x: 7, y: 1},
        {x: 1, y: 2}
    ]
        .map(this.treeCount)
        .reduce(product)
        .toString();

    private width = (): number => this.input[0].length;
    private height = (): number => this.input.length;

    private isTree = (v: Vector) => (this.input[v.y]?.[v.x % this.width()] ?? '') === '#';

    private add = (a: Vector, b: Vector): Vector => ({x: a.x + b.x, y: a.y + b.y});

    private treeCount = (direction: Vector): number => {
        let trees = 0;
        let position: Vector = {x: 0, y: 0};

        while (position.y < this.height()) {
            trees += Number(this.isTree(position));
            position = this.add(position, direction);
        }

        return trees;
    }
}

interface Vector {
    x: number;
    y: number;
}