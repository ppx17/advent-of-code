import {Vector} from "./helpers";

describe('Vector', () => {
    describe('element access', () => {
        it('returns the first element as x', () => {
            const v = new Vector(1, 2, 3);

            expect(v.x).toBe(1);
        });

        it('returns the second element as y', () => {
            const v = new Vector(1, 2, 3);

            expect(v.y).toBe(2);
        });

        it('returns the third element as z', () => {
            const v = new Vector(1, 2, 3);

            expect(v.z).toBe(3);
        });
    });

    describe('dimensions', () => {
        it('can count its dimensions', () => {
            const v = new Vector(1, 2, 3, 4);

            expect(v.dimensions).toBe(4);
        });
    });

    describe('add', () => {
        it('can add two positive vectors', () => {
            const a = new Vector(1, 2);
            const b = new Vector(3, 4);

            const sum = a.add(b);

            expect(sum.x).toBe(4);
            expect(sum.y).toBe(6);
        });
    });

    describe('within', () => {
        it('sees a negative vector as outside of a positive one', () => {
            const v = new Vector(-1, 0);
            const boundary = new Vector(2, 2);

            expect(v.within(boundary)).toBeFalsy();
        });

        it('sees a vector at the origin as inside of a positive one', () => {
            const v = new Vector(0, 0);
            const boundary = new Vector(2, 2);

            expect(v.within(boundary)).toBeTruthy();
        });

        it('sees a negative vector as inside of another negative one', () => {
            const v = new Vector(-1, -1);
            const boundary = new Vector(-2, -2);

            expect(v.within(boundary)).toBeTruthy();
        });
    });
})