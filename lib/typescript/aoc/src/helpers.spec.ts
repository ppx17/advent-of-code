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
            const v = Vector.zero();
            const boundary = new Vector(2, 2);

            expect(v.within(boundary)).toBeTruthy();
        });

        it('sees a negative vector as inside of another negative one', () => {
            const v = new Vector(-1, -1);
            const boundary = new Vector(-2, -2);

            expect(v.within(boundary)).toBeTruthy();
        });
    });

    describe('between', () => {
        it('sees a vector right in the middle of a positive pair as between', () => {
            const a = new Vector(3, 3);
            const b = new Vector(5, 5);
            const v = new Vector(4, 4);

            expect(v.between(a, b)).toBeTruthy();
        });

        it('sees a vector on the border of a positive pair as between', () => {
            const a = new Vector(3, 3);
            const b = new Vector(5, 5);
            const v = new Vector(3, 3);

            expect(v.between(a, b)).toBeTruthy();
        });
    });

    describe('manhattan', () => {
        it('can calculate the manhattan distance from origin', () => {
            const a = new Vector(3, 5);

            expect(a.manhattan(Vector.zero())).toBe(8);
        });

        it('can calculate the manhattan distance from origin to a negative position', () => {
            const a = new Vector(-4, -9);

            expect(a.manhattan(Vector.zero())).toBe(13);
        });

        it('can calculate the manhattan distance to a non-origin position', () => {
            const a = new Vector(4, 9);
            const b = new Vector(-3, -13);

            expect(a.manhattan(b)).toBe(29);
        });
    });

    describe('times', () => {
        it('can multiply a direction with a distance', () => {
            const result = Vector.north().times(10);
            expect(result.x).toBe(0);
            expect(result.y).toBe(-10);
        });
    })
})