import {Day} from "../aoc";

export class Day12 extends Day {
    private caves: Map<string, Cave> = new Map();
    day = (): number => 12;

    part1 = () => this.resolvePaths().size

    part2 = () =>
        Array.from(this.caves.values())
            .filter(c => !c.isBig && c.name !== 'start')
            .reduce((allRoutes, small) => {
                this.resolvePaths(small).forEach(r => allRoutes.add(r.serialize()))
                return allRoutes;
            }, new Set<string>()).size

    setup = () =>
        this.input.forEach(l => {
            const [a, b] = l.split('-').map(p => this.getOrMakeCave(p));
            a.connections.push(b); b.connections.push(a);
        });

    private resolvePaths(isBig?: Cave): Set<Path> {
        const start = this.caves.get('start');
        const paths: Set<Path> = new Set();
        const queue: Path[] = [new Path().add(start)];

        while(queue.length > 0) {
            const current = queue.pop();
            current.lastCave.connections
                .filter(conn => conn.isBig || !current.has(conn) || (conn === isBig && current.hasTimes(conn) < 2))
                .forEach(conn => {
                    const updatedPath = current.add(conn);
                    updatedPath.finished
                        ? paths.add(updatedPath)
                        : queue.push(updatedPath);
                });
        }
        return paths;
    }

    private getOrMakeCave(name: string): Cave {
        if(this.caves.has(name)) return this.caves.get(name);
        const c = new Cave(name);
        this.caves.set(name, c);
        return c;
    }
}

class Cave {
    public connections: Cave[] = [];
    public readonly isBig: boolean;
    constructor(public name: string) {
        this.isBig = name === this.name.toUpperCase();
    }
}

class Path {
    public finished: boolean = false;
    public lastCave: Cave;

    constructor(private readonly connections: Cave[] = []) {
        this.lastCave = connections[connections.length - 1];
    }

    has = (cave: Cave): boolean => !this.connections.every(c => c !== cave);
    hasTimes = (cave: Cave): number => this.connections.filter(c => c === cave).length;
    serialize = (): string => this.connections.map(c => c.name).join(',');
    add = (cave: Cave): Path => {
        const fork = new Path([...this.connections, cave]);
        fork.finished ||= cave.name === "end";
        return fork;
    };
}