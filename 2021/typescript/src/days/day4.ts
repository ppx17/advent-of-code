import {Day, sum} from "../aoc";

export class Day4 extends Day {
    day = (): number => 4;

    part1 = (): string => this.play(((currentBoard, calledNumber) => currentBoard.score(calledNumber).toString()))

    part2 = (): string =>
        this.play((currentBoard, calledNumber, boards) =>
            boards.length > 1
                ? boards.filter(x => x !== currentBoard)
                : currentBoard.score(calledNumber).toString())

    play(bingoProcedure: (currentBoard: Board, calledNumber: number, boards: Board[]) => string | Board[]): string {
        let boards = this.generateBoards();
        for(const n of this.allNumbers) {
            for(const currentBoard of boards) {
                currentBoard.call(n);
                if(currentBoard.hasBingo()) {
                    const res = bingoProcedure(currentBoard, n, boards);
                    if(typeof res === "string") return res;
                    boards = res;
                }
            }
        }
        return 'unknown';
    }

    private allNumbers: number[];
    private inputSegments: string[];

    setup() {
        super.setup();

        this.inputSegments = this.input.join("\n").split("\n\n");
        this.allNumbers = this.inputSegments.shift().split(",").map(Number);
    }

    private generateBoards = (): Board[] => this.inputSegments
        .map(b => b.split("\n").map(l => l.split(/\s+/).map(Number)))
        .map((b): Board => new Board(b));
}

class Board {
    all: number[];
    rows: number[][];

    constructor(numbers: number[][]) {
        this.all = numbers.flat();
        this.rows = [...numbers, ...Board.cols(numbers)];
    }

    public call(n: number): void {
        this.all = this.all.filter(x => x !== n);
        this.rows = this.rows.map(r => r.filter(x => x !== n));
    }

    public hasBingo = (): boolean => this.rows.find(r => r.length === 0) !== undefined;

    public score = (calledNumber: number): number => this.all.reduce(sum) * calledNumber;

    private static cols = (b: number[][]): number[][] => b[0].map((v, i) => b.map(x => x[i]));
}