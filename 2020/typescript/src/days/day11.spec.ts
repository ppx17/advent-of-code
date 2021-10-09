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

describe('day 11', () => {

    it('Run the part1 example correctly', () => {
        const sut = new Day11(example);
        sut.setup();

        expect(sut.part1()).toBe("37");
    });

    it('Run the part2 example correctly', () => {
        const sut = new Day11(example);
        sut.setup();

        expect(sut.part2()).toBe("26");
    });

})