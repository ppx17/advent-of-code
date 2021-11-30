import {Day} from "../aoc";
import {sum} from "./helpers";

export class Day7 extends Day {
    private bags = new Map<Color, Bag>();

    day = (): number => 7;

    part1 = (): string => this.countContainers('shiny gold').toString();

    part2 = (): string => this.countBagsRecursive('shiny gold').toString();

    setup() {
        super.setup();

        this.input
            .map(l => l.match(/^(?<bag>\w+ \w+) bags contain (?<content>(\d+ \w+ \w+ bags?,? ?)+)\.$/))
            .filter(r => r)
            .map((r): Bag => ({color: r.groups.bag, content: Day7.parseContent(r.groups.content)}))
            .forEach(b => this.bags.set(b.color, b));
    }

    private static parseContent(content: string): ContentLine[] {
        return content.split(',')
            .map(c => c.match(/(?<num>\d+) (?<color>\w+ \w+) bags?/))
            .map((m): ContentLine => ({color: m.groups.color, amount: Number(m.groups.num)}));
    }

    private countContainers(color: Color, seen: Set<Color> = new Set<Color>()): number {
        this.bags.forEach((bag) => {
            if (bag.content.filter(l => l.color === color).length > 0) {
                seen.add(bag.color);
                this.countContainers(bag.color, seen);
            }
        });

        return seen.size;
    }

    private countBagsRecursive(color: Color): number {
        return !this.bags.has(color)
            ? 0
            : this.bags.get(color)
                .content
                .map(line => line.amount + (line.amount * this.countBagsRecursive(line.color)))
                .reduce(sum);
    }
}

type Color = string;
type ContentLine = { color: Color, amount: number };
type Bag = { color: Color, content: ContentLine[] };
