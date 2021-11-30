import {Day} from "../aoc";

export class Day19 extends Day {
    private rules: Rule[];

    private readonly ELEVEN_RECURSION_LIMIT = 4;

    day = (): number => 19;

    part1 = (): string => {
        const reg = new RegExp(`^${this.resolveRule('0')}$`);
        return this.messages.filter(m => m.match(reg) !== null).length.toString();
    }

    part2 = (): string => {
        const fortyTwo = this.resolveRule('42');
        const thirtyOne = this.resolveRule('31');
        const eight = `${fortyTwo}+`;

        const eleven = Array.from({length: this.ELEVEN_RECURSION_LIMIT})
            .map((_v, i) => i+1)
            .map(i => `(${fortyTwo}{${i}}${thirtyOne}{${i}})`)
            .join('|');

        const reg = new RegExp(`^${eight}(${eleven})$`);

        return this.messages.filter(m => m.match(reg) !== null).length.toString();
    }
    private chars: Map<string, string>;
    private messages: string[];

    setup() {
        super.setup();

        this.rules = this.input.slice(0, this.input.indexOf(''))
            .map(s => s.split(': '))
            .map((p): Rule => ({nr: p[0], content: p[1]}));

        this.chars = new Map<string, string>();
        this.chars.set('|', '|');
        this.rules.filter(r => r.content[0] === '"').forEach(r => this.chars.set(r.nr, r.content[1]));

        this.messages = this.input.slice(this.input.indexOf(''), this.input.length);
    }

    private resolveRule(nr: string): string {
        const resolved = this.rules.find(r => r.nr === nr)
            .content
            .split(' ')
            .map(s => {
                if(this.chars.has(s)) return this.chars.get(s);

                return this.resolveRule(s);
            })
            .join('');

        return `(${resolved})`;
    }

}

type Rule = { nr: string, content: string};