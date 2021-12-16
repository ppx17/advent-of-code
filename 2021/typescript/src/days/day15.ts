import {Day, Vector} from "../aoc";

export class Day15 extends Day {
    day = (): number => 15;

    part1 = () => {
        return this.findCost(this.map);
    }
    part2 = () => {
        const expandedMap = [
            ...this.map,
            ...Day15.addMap(this.map, 1),
            ...Day15.addMap(this.map, 2),
            ...Day15.addMap(this.map, 3),
            ...Day15.addMap(this.map, 4),
        ];

        expandedMap.forEach(row => {
            const base = [...row];
            row.push(...Day15.addRow(base, 1));6
            row.push(...Day15.addRow(base, 2));
            row.push(...Day15.addRow(base, 3));
            row.push(...Day15.addRow(base, 4));
        })

        return this.findCost(expandedMap);
    }

    private static addMap = (map: number[][], inc: number): number[][] => map.map(row => this.addRow(row, inc));
    private static addRow = (row: number[], inc: number): number[] => row.map(n => this.add(n, inc));
    private static add = (num: number, inc: number): number => ((num + inc - 1) % 9) + 1;

    private findCost = (map: number[][]): number => {
        const nodeMap: (null | Node)[][] = [...map].map(l => l.map(() => null));

        const neighbors = [Vector.west(), Vector.north(), Vector.east(), Vector.south()];

        const bottomRight = new Vector(map[0].length - 1, map.length - 1);
        const firstNode = new Node(bottomRight, map[bottomRight.y][bottomRight.x]);
        nodeMap[bottomRight.y][bottomRight.x] = firstNode;

        const queue = new PriorityQueue<Node>();
        const openList = new Set<string>();
        const closedList = new Map<string, number>();

        queue.enqueue(firstNode, 1);
        openList.add(firstNode.id);

        while (queue.isNotEmpty()) {
            let current = queue.highest();

            openList.delete(current.id);

            if (current.dist() === 0) continue;

            neighbors
                .map(n => n.add(current.pos))
                .filter(n => n.within(bottomRight))
                .forEach(nv => {
                    const value = map[nv.y][nv.x];
                    let neighbor = nodeMap[nv.y][nv.x];
                    const newCost = current.cost + value;

                    // Route isn't better, abort.
                    if (neighbor !== null && neighbor.cost < newCost) return;

                    if (neighbor === null) {
                        neighbor = new Node(nv, newCost);
                        nodeMap[nv.y][nv.x] = neighbor;
                    } else {
                        neighbor.cost = newCost;
                    }

                    // Neighbor is already on queue, don't process twice
                    if (openList.has(neighbor.id)) return;

                    // Neighbor is already processed cheaper
                    if ((closedList.get(neighbor.id) ?? Number.MAX_SAFE_INTEGER) < neighbor.cost) return;

                    queue.enqueue(neighbor, neighbor.dist());
                    openList.add(neighbor.id);
                    closedList.set(neighbor.id, neighbor.cost);
                });
        }

        return closedList.get('0:0') - map[0][0];
    };

    setup = () => this.map = this.input.map(l => l.split('').map(Number));

    private map: number[][];
}

class Node {
    id: string;

    constructor(public pos: Vector, public cost: number) {
        this.id = pos.serialize();
    }

    dist = (): number => this.pos.x + this.pos.y;
}

class PriorityQueue<T> {

    private map: Map<number, T[]> = new Map();
    private topPriority: number = null;
    private itemCount = 0;

    enqueue = (element: T, priority: number) => {
        priority = Math.round(priority);

        if (!this.map.has(priority)) this.map.set(priority, []);

        this.map.get(priority).push(element);

        if (this.topPriority === null || priority > this.topPriority) this.topPriority = priority;
        this.itemCount++;
    }

    highest = (): T | undefined => {
        while (this.isNotEmpty()) {
            if (this.map.get(this.topPriority).length > 0) {
                this.itemCount--;
                return this.map.get(this.topPriority).shift();
            } else {
                this.map.delete(this.topPriority);
                this.topPriority = Math.max(...this.map.keys());
            }
        }

        return undefined;
    }

    isNotEmpty = (): boolean => this.size() !== 0;
    size = (): number => this.itemCount;
}