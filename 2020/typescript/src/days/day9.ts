import {Day} from "../aoc/day";
import {sum} from "./helpers";

export class Day9 extends Day {

    private numbers: number[];
    private readonly preamble = 25;
    private invalid: number = 0;

    day = (): number => 9;

    part1 = (): string => {
        for (let i = this.preamble; i < this.numbers.length; i++) {
            const curr = this.numbers[i];
            if (!this.hasSum(curr, i)) {
                this.invalid = curr;
                return curr.toString();
            }
        }


        return '';
    }

    part2 = (): string => {
        this.invalid ??= parseInt(this.part1());
        for (let start = 0; start < this.numbers.length; start++) {
            const result = this.fitEnd(start);
            if(result !== null) return result.toString();
        }
        return 'not found';
    }

    private fitEnd = (start: number): number | null => {
        for (let end = start + 1; end < this.numbers.length; end++) {
            const subset = this.numbers.slice(start, end);
            const sumOfNumbers = subset.reduce(sum);

            if (sumOfNumbers === this.invalid) return Math.min(...subset) + Math.max(...subset);
            if (sumOfNumbers > this.invalid) return null;
        }
        return null;
    };

    setup() {
        super.setup();

        this.numbers = this.input.map(s => parseInt(s));
    }

    private hasSum(curr: number, i: number) {
        for (let a = i - 1; a >= i - this.preamble; a--) {
            for (let b = a - 1; b >= i - this.preamble; b--) {
                if (this.numbers[a] + this.numbers[b] === curr) return true;
            }
        }

        return false;
    }
}
