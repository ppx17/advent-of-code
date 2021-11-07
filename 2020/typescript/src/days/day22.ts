import {Day} from "./day";

export class Day22 extends Day {
    day = (): number => 22;
    private stacks: Stacks;

    setup() {
        super.setup();
        const middle = this.input.indexOf('');
        this.stacks = new Stacks(
            new Stack(this.input.slice(1, middle).map(n => parseInt(n))),
            new Stack(this.input.slice(middle + 2).map(n => parseInt(n)))
        );
    }

    part1 = (): string => Day22.combat(this.stacks.clone()).toString()

    part2 = (): string => Day22.combat(this.stacks.clone(), true).toString();

    private static combat = (stacks: Stacks, recursive: boolean = false, isSubGame: boolean = false): Player | number => {
        const guard = new InfiniteGuard();

        while (stacks.bothContainCards()) {
            if (guard.hasSeen(stacks)) return Player.P1;

            const r = stacks.startRound();

            if(recursive && this.shouldPlayRecursive(r)) {
                this.combat(r.subStacks(), true, true) === Player.P1
                    ? r.stack1.addCards(r.p1, r.p2)
                    : r.stack2.addCards(r.p2, r.p1)
                continue;
            }

            r.p1 > r.p2
                ? r.stack1.addCards(r.p1, r.p2)
                : r.stack2.addCards(r.p2, r.p1)
        }

        return isSubGame
            ? stacks.winningPlayer()
            : stacks.winningScore();
    };

    private static shouldPlayRecursive(r: Round) {
        return r.stack1.containsAtLeastNCards(r.p1) && r.stack2.containsAtLeastNCards(r.p2);
    }
}

class Round {
    constructor(public p1: number, public p2: number, public stack1: Stack, public stack2: Stack) {
    }

    public subStacks = (): Stacks => new Stacks(this.stack1.subStack(this.p1), this.stack2.subStack(this.p2));
}

class Stacks {
    constructor(public readonly stack1: Stack, public readonly stack2: Stack) {
    }

    bothContainCards = (): boolean => this.stack1.notEmpty() && this.stack2.notEmpty();

    winningScore = (): number => this.stack1.notEmpty() ? this.stack1.score() : this.stack2.score();

    winningPlayer = (): Player => this.stack1.notEmpty() ? Player.P1 : Player.P2;

    clone = (): Stacks => new Stacks(this.stack1.clone(), this.stack2.clone());

    serialize = (): string => [this.stack1, this.stack2].map(s => s.serialize()).join('+');

    startRound = (): Round => new Round(this.stack1.takeCard(), this.stack2.takeCard(), this.stack1, this.stack2);
}

class InfiniteGuard {
    private readonly seenStacks: Map<string, boolean> = new Map();

    hasSeen(stacks: Stacks): boolean {
        const serial = stacks.serialize();
        const hasSeen = this.seenStacks.has(serial);
        this.seenStacks.set(serial, true);

        return hasSeen;
    }
}

class Stack {
    constructor(private numbers: number[]) {
    }

    takeCard = (): number => this.numbers.shift();

    addCards = (...numbers: number[]): number => this.numbers.push(...numbers);

    containsAtLeastNCards = (n: number): boolean => this.numbers.length >= n;

    isEmpty = (): boolean => this.numbers.length === 0;

    notEmpty = (): boolean => !this.isEmpty();

    subStack = (cardsToTake: number): Stack => new Stack([...this.numbers.slice(0, cardsToTake)]);

    clone = (): Stack => new Stack([...this.numbers]);

    serialize = (): string => this.numbers.join(',');

    score = (): number => this.numbers.reverse().map((v, i) => i * v).reduce((a, b) => a + b);
}

enum Player {
    P1,
    P2
}