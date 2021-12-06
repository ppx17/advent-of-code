import {Day} from "../aoc";
import {sum} from "./helpers";

export class Day10 extends Day {
    private adapters: number[];

    day = (): number => 10;

    part1 = (): string => {
        const diff = this.adapters.map((v, i, a) => i === 0 ? 0 : v - a[i - 1]);

        return (diff.filter(d => d === 1).length * diff.filter(d => d === 3).length).toString();
    }
    part2 = (): string => {
        const routeMap = new Map<number, number>();

        this.adapters.reverse().forEach((adapter, index) => {
            routeMap.set(adapter, index === 0
                ? 1
                : [1, 2, 3]
                    .filter(adapterOffset => routeMap.has(adapter + adapterOffset))
                    .map(adapterOffset => routeMap.get(adapter + adapterOffset))
                    .reduce(sum)
            );
        });

        return routeMap.get(0).toString();
    }

    setup(): void {
        const adapterBag = this.input.map(s => parseInt(s)).sort((a, b) => a - b);
        this.adapters = [
            0, // outlet
            ...adapterBag,
            Math.max(...adapterBag) + 3 // device
        ];
    }
}