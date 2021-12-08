import {Day, Vector} from "../aoc";

export class Day2 extends Day {
    day = (): number => 2;

    private course: Vector[];

    part1 = () => {
        const pos = this.course.reduce((pos, inst) => pos.add(inst), Vector.zero());
        return (pos.x * pos.y);
    }

    part2 = () => {
        let [pos, aim] = [Vector.zero(), Vector.zero()];
        this.course.forEach(inst => inst.y !== 0 ? aim = aim.add(inst) : pos = pos.add(aim.times(inst.x)).add(inst));
        return (pos.x * pos.y);
    }

    setup = () => {
        const dirs = { 'forward': Vector.east(), 'down': Vector.south(), 'up': Vector.north() };
        this.course = this.input.map(l => l.split(' ')).map(s => dirs[s[0]].times(Number(s[1])));
    };
}