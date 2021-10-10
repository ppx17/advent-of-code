import {Day12} from "./day12";

const sampleInput = [
    "F10",
    "N3",
    "F7",
    "R90",
    "F11",
];

describe('Day 12', () => {
    it('can process the part 1 example', () => {
        const sut = new Day12(sampleInput);

        expect(sut.part1()).toBe("25");
    });

    it('can process the part 2 example', () => {
        const sut = new Day12(sampleInput);

        expect(sut.part2()).toBe('286');
    });
});