import {Day3} from "./day3";

describe('Day 3', () => {
    const input = [
        '00100',
        '11110',
        '10110',
        '10111',
        '10101',
        '01111',
        '00111',
        '11100',
        '10000',
        '11001',
        '00010',
        '01010',
    ];

    const p = new Day3(input);
    p.setup();

    it('should run part 1 with sample input', () => {
        expect(p.part1()).toBe('198');
    });

    it('should run part 2 with sample input', () => {
        expect(p.part2()).toBe('230');
    });
});