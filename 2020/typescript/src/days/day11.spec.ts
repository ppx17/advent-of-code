import {Day11} from "./day11";

const example = [
    "L.LL.LL.LL",
    "LLLLLLL.LL",
    "L.L.L..L..",
    "LLLL.LL.LL",
    "L.LL.LL.LL",
    "L.LLLLL.LL",
    "..L.L.....",
    "LLLLLLLLLL",
    "L.LLLLLL.L",
    "L.LLLLL.LL"
];

describe('Day 11', () => {

    it('can process the part 2 example', () => {
        const sut = new Day11(example).initializeDay();

        expect(sut.part1()).toBe("37");
    });

    it('can process the part 2 example', () => {
        const sut = new Day11(example).initializeDay();

        expect(sut.part2()).toBe("26");
    });

})