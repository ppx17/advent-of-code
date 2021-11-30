import {Day} from "../aoc";

export class Day1 extends Day {
    private amounts: number[] = [];

    day = (): number => 1;

    part1(): string {
        for (const a of this.amounts) {
            for (const b of this.amounts) {
                if (a + b === 2020) return (a * b).toString();
            }
        }

        return '';
    }

    part2(): string {
        for (const a of this.amounts) {
            for (const b of this.amounts) {
                for (const c of this.amounts) {
                    if (a + b + c === 2020) return (a * b * c).toString();
                }
            }
        }

        return '';
    }

    setup() {
        super.setup();

        this.amounts = this.input.map((i) => parseInt(i));
    }
}