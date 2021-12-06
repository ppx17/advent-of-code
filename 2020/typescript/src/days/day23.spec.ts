import {Day23} from "./day23";

const sampleInput = ['389125467'];

describe('Day 23', () => {
    describe('part 1', () => {
        it('can handle the example', () => {
            const sut = new Day23(sampleInput).initializeDay();

            expect(sut.part1(10)).toBe('92658374');
            expect(sut.part1(100)).toBe('67384529');
        });
    });
    describe('part 2', () => {
        it('can handle the example', () => {
            const sut = new Day23(sampleInput).initializeDay();

            expect(sut.part2()).toBe('149245887792');
        });
    });
});

