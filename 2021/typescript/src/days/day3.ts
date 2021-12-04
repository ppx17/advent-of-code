import {Day, product} from "../aoc";

export class Day3 extends Day {
    day = (): number => 3;

    part1 = (): string => {
        const counts = this.count(this.input);
        return [
            parseInt(counts.map(n => n > 0 ? 1 : 0).join(''), 2),
            parseInt(counts.map(n => n < 0 ? 1 : 0).join(''), 2)
        ].reduce(product).toString();
    }

    part2 = (): string => {
        return [
            parseInt(this.reduceOptions(x => x >= 0 ? '1' : '0'), 2),
            parseInt(this.reduceOptions(x => x < 0 ? '1' : '0'), 2)
        ].reduce(product).toString();
    }

    private reduceOptions(f: (count: number) => '1'|'0'): string {
        let options = [...this.input];

        for(let i = 0; i < this.input[0].length; i++) {
            if(options.length === 1) return options[0];
            let counts = this.count(options);
            options = options.filter(s => s[i] === f(counts[i]));
        }

        return options[0];
    }

    private count(report: string[]): number[] {
        return report
            .map(l => l.split('').map((c): number => c === '0' ? -1 : 1))
            .reduce((a, b) => a.map((v, i) => v + b[i]));
    }
}
