import {Day} from "../aoc";

export class Day18 extends Day {
    day = (): number => 18;

    part1 = () => magnitude(this.input.reduce(snailAdd))

    part2 = () => this.input
        .map(a => this.input
            .map(b => a === b ? 0 : magnitude(snailAdd(a,b)))
        )
        .flat()
        .reduce((a, b) => a > b ? a : b)

    setup = () => {}
}

export function snailAdd(a: string, b: string): string {
    return reduce(`[${a},${b}]`);
}

export function reduce(input: string): string {
    let res: Result = {output: input, hasPerformed: true}
    while(res.hasPerformed) {
        res = explode(res.output);
        if(res.hasPerformed) continue;

        res = split(res.output);
    }
    return res.output;
}

export function explode(input: string): Result {
    const match = input.matchAll(/(?<all>\[(?<left>\d+),(?<right>\d+)])/g);

    for(const m of match) {
        const leftOfMatch = input.substring(0, m.index);
        if(level(leftOfMatch) >= 4) {
            const rightOfMatch = input.substring(m.index + m.groups['all'].length);
            const [left, right] = [Number(m.groups['left']), Number(m.groups['right'])];
            return {
                output: `${addToLastNumberInString(leftOfMatch, left)}0${addToFirstNumberInString(rightOfMatch,right)}`,
                hasPerformed: true
            };
        }
    }

    return {output: input, hasPerformed: false};
}

export function split(input: string): Result {
    let hasPerformed = false;
    const output = input.replace(/\d{2}/, m => {
        const n = Number(m);
        hasPerformed = true;
        return `[${Math.floor(n/2)},${Math.ceil(n/2)}]`
    });

    return {output, hasPerformed};
}

function level(input: string): number {
    let level = 0;
    for(let i = 0; i < input.length; i++) {
        if(input.charAt(i) === '[') level++;
        else if(input.charAt(i) === ']') level--;
    }
    return level;
}

export function magnitude(input: string): number {
    while( ! input.match(/^\d+$/)) {
        input = input.replaceAll(/\[\d+,\d+]/g, (substr) => {
            const m = substr.match(/\[(?<a>\d+),(?<b>\d+)]/);
            return (3 * Number(m.groups['a']) + 2 * Number(m.groups['b'])).toString();
        });
    }
    return Number(input);
}

export type Result = {
    output: string,
    hasPerformed: boolean
}

function addToLastNumberInString(v: string, value: number): string {
    return v.replace(/\d+(?!.*\d)/, match => (Number(match) + value).toString());
}

function addToFirstNumberInString(v: string, value: number): string {
    return v.replace(/\d+/, match => (Number(match) + value).toString());
}
