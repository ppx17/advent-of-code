import {Day22} from "./day22";

const sampleInput = [
    'Player 1:',
    '9',
    '2',
    '6',
    '3',
    '1',
    '',
    'Player 2:',
    '5',
    '8',
    '4',
    '7',
    '10'
];

describe('Day 22', () => {
    describe('part 1', () => {
        it('can handle example 1', () => {
            const sut = new Day22(sampleInput);
            sut.setup();

            expect(sut.part1()).toBe('306');
        });
    });

    describe('part 2', () => {
        it('can handle example 1', () => {
            const sut = new Day22(sampleInput);
            sut.setup();

            expect(sut.part2()).toBe('291');
        });
    });
});