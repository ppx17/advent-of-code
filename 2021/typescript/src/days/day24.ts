import {Day} from "../aoc";

export class Day24 extends Day {
    day = (): number => 24;

    part1 = () => Compiled.find()
    part2 = () => {
        Compiled.allNumbers.reverse();
        return Compiled.find();
    }

    setup = () => {
        this.input.forEach((v, i) => {
            if (i % 18 === 5) Compiled.pairs.push({chk: Number(v.substring(6)), off: 0});
            if (i % 18 === 15) Compiled.pairs[Compiled.pairs.length - 1].off = Number(v.substring(6));
        });
    };
}

class Compiled {
    public static pairs: Pair[] = [];
    public static allNumbers = [9, 8, 7, 6, 5, 4, 3, 2, 1];

    public static find(i: number = 0, z: number = 0): number {
        const pair = this.pairs[i];

        if (i + 1 == this.pairs.length) {
            for (let input of this.allNumbers)
                if (this.subroutine(z, input, pair) === 0) return input;
            return 0;
        }

        let options: number[];
        if (pair.chk > 0) {
            options = this.allNumbers;
        } else {
            const  x = (z % 26) + pair.chk;
            if (x <= 0 || x > 9) return 0;
            options = [x];
        }

        for (const input of options) {
            const res = this.find(i + 1, this.subroutine(z, input, pair));
            if (res > 0) return res + (input * Math.pow(10, this.pairs.length - 1 - i));
        }

        return 0;
    }

    private static subroutine(z: number, input: number, pair: Pair): number {
        const bool = Number((z % 26) + pair.chk !== input);
        if (pair.chk < 1) z = Math.floor(z / 26);
        z *= (25 * bool + 1);
        z += (input + pair.off) * bool;

        return z;
    }
}

type Pair = { chk: number, off: number };