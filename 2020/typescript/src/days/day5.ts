import {Day} from "./day";

export class Day5 extends Day {
    private ids: number[] = [];

    day = (): number => 5;

    part1 = (): string => this.max().toString()

    part2 = (): string =>
        this.range(this.min(), this.max())
            .find((id: number) =>
                !this.exists(id) && this.exists(id + 1) && this.exists(id - 1)
            )
            .toString();

    setup = () => {
        super.setup();

        this.ids = this.input
            .map(l => l.replaceAll(/[FL]/g, '0').replaceAll(/[BR]/g, '1'))
            .map(binary => parseInt(binary, 2))
            .filter(n => !isNaN(n));
    };

    private min = (): number => Math.min(...this.ids);
    private max = (): number => Math.max(...this.ids);

    private exists = (id: number): boolean => this.ids.indexOf(id) !== -1;

    private range = (start: number, end: number): number[] =>
        Array.from({length: end - start + 1}, (_, i) => i + start);
}

