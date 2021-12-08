import {Day, sum} from "../aoc";

export class Day8 extends Day {
    day = (): number => 8;

    part1 = () =>
        this.patterns.map(p => p[1])
            .map(w => w.filter(x => x.length !== 5 && x.length !== 6).length)
            .reduce(sum);

    part2 = () =>
        this.patterns
            .map(p => {
                const map = this.resolve(p[0]);
                return Number(p[1].map(n => map.get(this.sort(n))).join(''));
            })
            .reduce(sum);

    private resolve(all: string[]): Map<string, number> {
        all = all.map(this.sort);
        const certain = new Map<string, number>();

        const one = all.find(o => o.length === 2);
        certain.set(one, 1);
        certain.set(all.find(o => o.length === 3), 7);
        certain.set(all.find(o => o.length === 4), 4);
        certain.set(all.find(o => o.length === 7), 8);

        all = all.filter(o => o.length === 5 || o.length === 6);

        // three is the only one left with 5 segments that fully contains 1
        const three = all.find(s => s.length === 5 && this.fullyContains(s, one));

        certain.set(three, 3);
        all = all.filter(o => o !== three);

        // six is the only one with 6 segments that does not fully contain 1
        const six = all.find(s => s.length === 6 && !this.fullyContains(s, one));
        certain.set(six, 6);
        all = all.filter(o => o !== six);

        // nine is the only one with 6 segments that contains three
        const nine = all.find(s => s.length === 6 && this.fullyContains(s, three));
        certain.set(nine, 9);
        all = all.filter(o => o !== nine);

        // zero is the only 6 segments left
        const zero = all.find(s => s.length === 6 && s != nine);
        certain.set(zero, 0);
        all = all.filter(o => o !== zero);

        // five is fully contained in six
        const five = all.find(s => this.fullyContains(six, s));
        certain.set(five, 5);

        // two is only one left
        certain.set(all.find(o => o !== five), 2);

        return certain;
    }

    private sort = (s: string) => s.split('').sort().join('');
    private fullyContains = (container: string, contains: string) => contains.split('').every(c => container.includes(c));

    setup = () => this.patterns = this.input.map(s => s.split(' | ').map(l => l.split(' ')));
    private patterns: string[][][];
}