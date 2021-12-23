import {Day, Vector} from "../aoc";

export class Day23 extends Day {
    day = (): number => 23;

    part1 = () => {
        this.queue = [];
        this.queue.push({grid: Day23.copyGrid(this.grid), energy: 0});

        return this.processQueue(17_000);
    }

    part2 = () => {
        this.rooms.A.push(...['3:4', '3:5'].map(Vector.deserialize));
        this.rooms.B.push(...['5:4', '5:5'].map(Vector.deserialize));
        this.rooms.C.push(...['7:4', '7:5'].map(Vector.deserialize));
        this.rooms.D.push(...['9:4', '9:5'].map(Vector.deserialize));

        this.queue = [];

        const grid = [
            ...this.grid.slice(0, 3),
            '  #D#C#B#A#'.split(''),
            '  #D#B#A#C#'.split(''),
            ...this.grid.slice(3),
        ];

        this.queue.push({grid, energy: 0});

        return this.processQueue(50_000);
    }

    setup = () => this.grid = this.input.map(l => l.split(''));

    private processQueue(lowestGuess: number): number {
        let lowestEnergy = lowestGuess;

        const seen = new Map<string, number>();
        while (this.queue.length > 0) {
            const currentState = this.queue.pop();

            if (currentState.energy >= lowestEnergy) continue;

            const serialized = Day23.serialize(currentState);
            if (seen.has(serialized) && seen.get(serialized) <= currentState.energy) continue;
            seen.set(serialized, currentState.energy);

            if (this.isComplete(currentState.grid)) {
                lowestEnergy = Math.min(currentState.energy, lowestEnergy);
                continue;
            }

            this.step(currentState);
        }
        return lowestEnergy;
    }

    private step(state: GridState) {
        this.hallwaySpots
            .filter(v => this.rooms[state.grid[v.y][v.x]] !== undefined)
            .forEach(v => this.attemptMoveBackToRoom(state, v, this.rooms[state.grid[v.y][v.x]]));

        Object.entries(this.rooms)
            .forEach(([letter, room]) => this.attemptMoveFromRoom(state, room, letter));
    }

    private attemptMoveFromRoom(state: GridState, room: Vector[], roomLetter: string) {
        if (room.every(v => state.grid[v.y][v.x] === roomLetter || Day23.isFree(v, state))) return;

        const source = room.find(v => !Day23.isFree(v, state));

        this.hallwaySpots
            .filter(h  => Day23.isFree(h, state))
            .forEach(hall => this.attemptMoveTo(state, source, hall));
    }

    private attemptMoveBackToRoom(state: GridState, pos: Vector, room: Vector[]) {
        if (room.some(v => state.grid[v.y][v.x] !== state.grid[pos.y][pos.x] && !Day23.isFree(v, state))) return;
        this.attemptMoveTo(state, pos, room.slice().reverse().find(v => Day23.isFree(v, state)));
    }

    private attemptMoveTo(state: GridState, source: Vector, target: Vector) {
        const c = Day23.routeCost(source, target, state);
        if (c === undefined) return;
        const letter = state.grid[source.y][source.x];

        this.queue.push(Day23.copyState(state, c * Day23.costPerStep(letter), g => {
            g[target.y][target.x] = letter;
            g[source.y][source.x] = '.';
        }));
    }

    private static copyState(state: GridState, usedEnergy: number, modify: (grid: string[][]) => void = () => {}): GridState {
        const grid = Day23.copyGrid(state.grid, modify);
        return {grid, energy: state.energy + usedEnergy};
    }

    private static copyGrid(grid: string[][], modify: (grid: string[][]) => void = () => {}): string[][] {
        const copy = grid.map(r => r.slice()).slice();
        modify(copy);
        return copy;
    }

    private static routeCost(pos: Vector, target: Vector, state: GridState): number {
        let steps = pos.y - 1;
        pos = new Vector(pos.x, 1);
        const xStep = new Vector(Math.sign(target.x - pos.x), 0);
        while (pos.x !== target.x) {
            pos = pos.add(xStep);
            steps++;
            if (!Day23.isFree(pos, state)) return;
        }
        while (pos.y < target.y) {
            pos = pos.add(Vector.south());
            steps++;
            if (!Day23.isFree(pos, state)) return;
        }
        return steps;
    }

    private static serialize = (state: GridState): string => state.grid.map(r => r.join('')).join('');

    private static isFree = (pos: Vector, state: GridState): boolean => state.grid[pos.y][pos.x] === '.';

    private static costPerStep(letter: string): number {
        return {'A': 1, 'B': 10, 'C': 100, 'D': 1000}[letter] ?? 0;
    }

    private isComplete(grid: string[][]) {
        for (const [letter, room] of Object.entries(this.rooms))
            if (!room.every(v => grid[v.y][v.x] === letter)) return false;

        return true;
    }

    private readonly hallwaySpots: Vector[] = ['1:1', '2:1', '4:1', '6:1', '8:1', '10:1', '11:1'].map(Vector.deserialize);
    private readonly rooms = {
        "A": ['3:2', '3:3'].map(Vector.deserialize),
        "B": ['5:2', '5:3'].map(Vector.deserialize),
        "C": ['7:2', '7:3'].map(Vector.deserialize),
        "D": ['9:2', '9:3'].map(Vector.deserialize)
    };
    private grid: string[][];
    private queue: GridState[];
}

type GridState = {
    grid: string[][];
    energy: number;
}
