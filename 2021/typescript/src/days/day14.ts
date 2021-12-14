import {Day} from "../aoc";

export class Day14 extends Day {
    day = (): number => 14;

    part1 = () => this.stepTimes(10);
    part2 = () => this.stepTimes(40);

    private stepTimes(times: number): number {
        let pairCounts = new Map<string, number>();
        for (let i = 1; i < this.template.length; i++) Day14.mapAdd(pairCounts, this.template.substring(i - 1, i + 1));
        for (let s = 0; s < times; s++) pairCounts = this.stepCounts(pairCounts);

        const lc = new Map<string, number>();
        pairCounts.forEach((count, pair) => pair.split('').forEach(c => Day14.mapAdd(lc, c, count)));

        return (v => Math.max(...v) - Math.min(...v))([...lc.values()].map(n => Math.ceil(n / 2)));
    }

    private stepCounts(counts: Map<string, number>): Map<string, number> {
        const result = new Map<string, number>();

        this.rules.forEach((insert, pair) => {
            if (!counts.has(pair)) return;
            Day14.mapAdd(result, pair[0] + insert, counts.get(pair));
            Day14.mapAdd(result, insert + pair[1], counts.get(pair));
        });
        return result;
    }

    setup = () => {
        this.template = this.input[0];
        this.rules = new Map<string, string>(this.input.slice(2).map(p => p.split(' -> ')) as [string, string][]);
    };

    private static mapAdd = <K>(m: Map<K, number>, k: K, i: number = 1) => m.set(k, (m.get(k) ?? 0) + i);

    private template: string;
    private rules: Map<string, string>;
}
