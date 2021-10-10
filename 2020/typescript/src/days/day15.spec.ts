import {Day15} from "./day15";

describe('Day 15', () => {
    it('can process the first example', () => {
        const sut = new Day15(['0,3,6']);

        expect(sut.play(10)).toBe('0');
    });

    it('can process part 1 example 1', () => {
        const sut = new Day15(['1,3,2']);

        expect(sut.play()).toBe('1');
    });

    it('can process part 1 example 2', () => {
        const sut = new Day15(['2,1,3']);
        expect(sut.play()).toBe('10');
    });

    it('can process part 1 example 3', () => {
        const sut = new Day15(['1,2,3']);

        expect(sut.play()).toBe('27');
    });

    it('can process part 1 example 4', () => {
        const sut = new Day15(['2,3,1']);

        expect(sut.play()).toBe('78');
    });
});