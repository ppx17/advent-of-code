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

    setup = () => {
        this.calculator = new Calculator();
        this.parsed = this.input.map(l => Parser.parseLine(l));
    };

    private calculator: Calculator;
    private parsed: Pair[];
}

export class Parser {
    public static parseLine(l: string): Pair {
        let level = 0;
        const pairs: Pair[] = [];
        let last: Pair | undefined;

        for (let i = 0; i < l.length; i++) {
            if (l[i] === '[') {
                level++;
                const pair: Pair = new Pair(level);
                if (pairs.length > 0) {
                    const parent = pairs[pairs.length - 1];
                    pair.parent = parent;
                    if (parent.left === undefined) {
                        parent.left = pair;
                    } else if (parent.right === undefined) {
                        parent.right = pair;
                    }
                }
                pairs.push(pair);
            } else if (l[i] === ']') {
                level--;
                last = pairs.pop();
            } else if (l[i].match(/[0-9]/)) {
                let n = l[i];
                if ((l[i + 1] ?? '').match(/[0-9]/)) n += l[++i];

                const parent = pairs[pairs.length - 1];
                if (parent.left === undefined) {
                    parent.left = new Scalar(level + 1, Number(n), parent);
                } else if (parent.right === undefined) {
                    parent.right = new Scalar(level + 1, Number(n), parent);
                }
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

        if (reduced instanceof Scalar) throw "Reduce should never return a scalar as end result.";

        return reduced;
    }

    public reduce(pair: Scalar | Pair): Scalar | Pair {
        this.actionExecuted = true;
        while (this.actionExecuted) {
            console.log(pair.toString());
            this.actionExecuted = false;
            pair = this.explode(pair);
            if (this.actionExecuted) continue;

            pair = this.split(pair);

            console.log(this.actionExecuted);
        }

        return pair;
    }

    public explode(pair: Pair | Scalar): Scalar | Pair {
        if (pair instanceof Scalar) return pair;

        if (pair.level > 4 && pair.left instanceof Scalar && pair.right instanceof Scalar) {

            let p = pair.parent;
            let child = pair;

            while (p !== undefined) {
                if (p.left instanceof Scalar) {
                    p.left.addToRight(pair.left.value);
                    break;
                } else {
                    if (p.left === child) {
                        child = p;
                        p = p.parent;
                    } else {
                        p.left.addToRight(pair.left.value);
                        break;
                    }
                }
            }

            p = pair.parent;
            child = pair;
            while (p !== undefined) {
                if (p.right instanceof Scalar) {
                    p.right.addToLeft(pair.right.value);
                    break;
                } else {
                    if (p.right === child) {
                        child = p;
                        p = p.parent;
                    } else {
                        p.right.addToLeft(pair.right.value);
                        break;
                    }
                }
            }

            this.actionExecuted = true;
            return new Scalar(pair.level, 0, pair.parent);
        }


        if (!this.actionExecuted) pair.left = this.explode(pair.left);
        if (!this.actionExecuted) pair.right = this.explode(pair.right);

        return pair;
    }

    public split(pair: Pair | Scalar): Pair | Scalar {
        if (pair instanceof Scalar) return pair;

        if (pair.left instanceof Scalar && pair.left.shouldSplit()) {
            this.actionExecuted = true;
            pair.left = pair.left.split();
            return pair;
        }

        if (!this.actionExecuted) pair.left = this.split(pair.left);

        if (!this.actionExecuted && pair.right instanceof Scalar && pair.right.shouldSplit()) {
            this.actionExecuted = true;
            pair.right = pair.right.split();
            return pair;
        }
        if (!this.actionExecuted) pair.right = this.split(pair.right);
        return pair;
    }
}

abstract class Data {
    parent?: Pair;

    constructor(public level: number) {
    }

    abstract toString(): string;
}

export class Pair extends Data {
    left?: Scalar | Pair;
    right?: Scalar | Pair;

    toString = (): string => `[${this.left.toString()},${this.right.toString()}]`;

    levelUp() {
        this.left?.levelUp();
        this.right?.levelUp();
        this.level++;
    }

    addToLeft = (inc: number) => this.left.addToLeft(inc);

    addToRight = (inc: number) => this.right.addToRight(inc);

    magnitude = (): number => 3 * this.left.magnitude() + 2 * this.right.magnitude();
}

export class Scalar extends Data {
    constructor(level: number, public value: number, parent: Pair) {
        super(level);
        this.parent = parent;
    }

    magnitude(): number {
        return this.value;
    }

    levelUp = () => this.level++;

    addToLeft = (inc: number) => this.value += inc;
    addToRight = (inc: number) => this.value += inc;

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