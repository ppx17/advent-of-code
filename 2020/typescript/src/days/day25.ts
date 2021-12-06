import {Day} from "../aoc";

export class Day25 extends Day {
    day = () => 25;

    part1(): string {
        let [cardPubKey, doorPubKey, mod, key] = [...this.input.map(Number), 2020_12_27, 1];
        for (let loop = 1; loop !== cardPubKey; loop *= 7, loop %= mod) key = (key * doorPubKey) % mod;
        return key.toString();
    }

    part2 = (): string => '';
}