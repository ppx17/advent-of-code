import {Day14} from "./day14";


describe('Day 14', () => {
    it('can mask', () => {
        expect(Day14.applyMask('XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X', 11n)).toBe(73n);
    });

    it('can process the part 1 example', () => {
        const program = [
            'mask = XXXXXXXXXXXXXXXXXXXXXXXXXXXXX1XXXX0X',
            'mem[8] = 11',
            'mem[7] = 101',
            'mem[8] = 0'
        ];

        const sut = new Day14(program).initializeDay();

        expect(sut.part1()).toBe("165");
    });

    it('can expand addresses with floating bits', () => {
        const result = Day14.expandAddress('000000000000000000000000000000X1001X', 42n);

        expect(result).toHaveLength(4);

        expect(result.indexOf(26n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(27n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(58n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(59n)).toBeGreaterThanOrEqual(0);
    });

    it('can expand addresses with floating bits, sample 2', () => {
        const result = Day14.expandAddress('00000000000000000000000000000000X0XX', 26n);

        expect(result).toHaveLength(8);

        expect(result.indexOf(16n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(17n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(18n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(19n)).toBeGreaterThanOrEqual(0);

        expect(result.indexOf(24n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(25n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(26n)).toBeGreaterThanOrEqual(0);
        expect(result.indexOf(27n)).toBeGreaterThanOrEqual(0);
    });

    it('can process the part 2 example', () => {
        const program = [
            'mask = 000000000000000000000000000000X1001X',
            'mem[42] = 100',
            'mask = 00000000000000000000000000000000X0XX',
            'mem[26] = 1',
        ];

        const sut = new Day14(program).initializeDay();

        expect(sut.part2()).toBe('208');
    })
});