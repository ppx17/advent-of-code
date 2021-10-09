import {Day} from "./day";
import {Tools} from "../tools";

export class Day6 extends Day {
    private groups: string[] = [];

    day = (): number => 6;

    part1 = (): string => this.groups
        .map(group => group.replaceAll(/\n/g, '').split(''))
        .map(group => new Set(group).size)
        .reduce((a, b) => a + b)
        .toString()

    part2 = (): string => this.groups
        .map(group => group
            .split(/\r?\n/)
            .map(person => new Set(person.split('')))
            .reduce((personA, personB) => new Set([...personA].filter(ans => personB.has(ans))))
            .size
        )
        .reduce((a, b) => a + b)
        .toString()

    setup = () => {
        super.setup();

        this.groups = Tools.inputString(this.day()).trim().split(/\r?\n\r?\n/);
    };
}
