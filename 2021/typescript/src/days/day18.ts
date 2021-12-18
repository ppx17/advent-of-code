import {Day} from "../aoc";

export class Day18 extends Day {
    day = (): number => 18;

    part1 = () =>
        this.input
            .map(l => Parser.parseLine(l))
            .reduce((a, b) => this.calculator.add(a, b))
            .magnitude();

    part2 = () => this.input
        .map(a => this.input
            .map(b => a === b ? 0 : this.calculator.add(Parser.parseLine(a), Parser.parseLine(b)).magnitude())
        )
        .flat()
        .reduce((a, b) => a > b ? a : b);

    setup = () => this.calculator = new Calculator();

    private calculator: Calculator;
}

export class Parser {
    public static parseLine(l: string): Pair {
        let level = 0, last: Pair | undefined;
        const pairs: Pair[] = [];

        for (let i = 0; i < l.length; i++) {
            if (l[i] === '[') {
                level++;
                const pair: Pair = new Pair(level);
                if (pairs.length > 0) {
                    const parent = pairs[pairs.length - 1];
                    pair.parent = parent;
                    parent.left === undefined
                        ? parent.left = pair
                        : parent.right = pair;
                }
                pairs.push(pair);
            } else if (l[i] === ']') {
                level--;
                last = pairs.pop();
            } else if (l[i].match(/[0-9]/)) {
                let n = l[i];
                while ((l[i + 1] ?? '').match(/[0-9]/)) n += l[++i];

                const parent = pairs[pairs.length - 1];
                const scalar = new Scalar(level + 1, Number(n), parent)
                parent.left === undefined ? parent.left = scalar : parent.right = scalar;
            }
        }

        return last;
    }
}

export class Calculator {
    private actionExecuted = false;

    public add(a: Pair, b: Pair): Pair {
        a.levelUp();
        b.levelUp();

        const result = new Pair(1);
        result.left = a;
        result.right = b;

        a.parent = result;
        b.parent = result;

        const reduced = this.reduce(result);

        if (reduced.isScalar()) throw "Reduce should never return a scalar as end result.";

        return reduced;
    }

    public reduce(pair: Scalar | Pair): Scalar | Pair {
        this.actionExecuted = true;
        while (this.actionExecuted) {
            this.actionExecuted = false;
            pair = this.explode(pair);
            if (this.actionExecuted) continue;

            pair = this.split(pair);
        }

        return pair;
    }

    public explode(pair: Pair | Scalar): Scalar | Pair {
        if (pair.isScalar()) return pair;

        if (pair.shouldExplode() && pair.left.isScalar() && pair.right.isScalar()) {
            pair.addToParentOnLeft(pair.left.value);
            pair.addToParentOnRight(pair.right.value);

            this.actionExecuted = true;
            return new Scalar(pair.level, 0, pair.parent);
        }


        pair.left = this.explode(pair.left);
        if (!this.actionExecuted) pair.right = this.explode(pair.right);

        return pair;
    }

    public split(pair: Pair | Scalar): Pair | Scalar {
        if (pair instanceof Scalar) return pair;

        if (pair.left.isScalar() && pair.left.shouldSplit()) {
            this.actionExecuted = true;
            pair.left = pair.left.split();
            return pair;
        }

        pair.left = this.split(pair.left);

        if (this.actionExecuted) return pair;

        if (pair.right.isScalar() && pair.right.shouldSplit()) {
            this.actionExecuted = true;
            pair.right = pair.right.split();
            return pair;
        }

        pair.right = this.split(pair.right);
        return pair;
    }
}

abstract class Data {
    parent?: Pair;

    constructor(public level: number) {
    }

    isScalar = (): this is Scalar => this instanceof Scalar;

    levelUp() {
        this.level++;
    }

    abstract toString(): string;
}

export class Pair extends Data {
    left?: Scalar | Pair;
    right?: Scalar | Pair;

    toString = (): string => `[${this.left.toString()},${this.right.toString()}]`;

    levelUp() {
        this.level++;
        this.left?.levelUp();
        this.right?.levelUp();
    }

    addToParentOnLeft = (inc: number) => {
        if (this.parent === undefined) return;

        this.parent.left === this
            ? this.parent.addToParentOnLeft(inc)
            : this.parent.left.addToRight(inc);
    }

    addToParentOnRight = (inc: number) => {
        if (this.parent === undefined) return;

        this.parent.right === this
            ? this.parent.addToParentOnRight(inc)
            : this.parent.right.addToLeft(inc);
    }

    addToLeft = (inc: number) => this.left.addToLeft(inc);
    addToRight = (inc: number) => this.right.addToRight(inc);

    shouldExplode = () => this.level > 4;

    magnitude = (): number => 3 * this.left.magnitude() + 2 * this.right.magnitude();
}

export class Scalar extends Data {
    constructor(level: number, public value: number, parent: Pair) {
        super(level);
        this.parent = parent;
    }

    magnitude = (): number => this.value;

    increment = (inc: number) => this.value += inc;
    addToLeft = this.increment;
    addToRight = this.increment;

    split(): Pair {
        const pair = new Pair(this.level);
        pair.left = new Scalar(this.level + 1, Math.floor(this.value / 2), pair);
        pair.right = new Scalar(this.level + 1, Math.ceil(this.value / 2), pair);
        pair.parent = this.parent;
        return pair;
    }

    shouldSplit = (): boolean => this.value >= 10;
    toString = (): string => this.value.toString();

}