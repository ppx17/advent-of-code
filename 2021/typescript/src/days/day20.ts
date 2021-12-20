import {Day} from "../aoc";

export class Day20 extends Day {
    day = (): number => 20;

    part1 = () => this.enhanceCycles(2);
    part2 = () => this.enhanceCycles(50);

    setup = () => {
        this.bits = this.input[0].split('').map(this.bit);
        this.image = this.input.slice(2).map(l => l.split('').map(this.bit));
    };

    private enhanceCycles = (cycles: number): number =>
        this.array(cycles, (_, i): Pixel => i % 2 === 0 ? 0 : 1)
            .reduce<Pixel[][]>((img, p) => this.enhance(img, p), this.image)
            .flat()
            .reduce((a, b) => a + b, 0);

    private enhance = (image: Pixel[][], padding: Pixel): Pixel[][] => {
        const result = this.array(image.length + 2, () => this.array<Pixel>(image[0].length + 2, () => 0));

        for (let y = 0; y < result.length; y++)
            for (let x = 0; x < result[0].length; x++)
                result[y][x] = this.bits[this.offsets
                    .map(o => (image[y + o[1] - 1] ?? [])[x + o[0] - 1] ?? padding)
                    .reduce((acc, p): number => (acc << 1) | p, 0)];

        return result;
    };

    private bit = (c: string) => c === '#' ? 1 : 0;
    private array = <T>(l: number, init: (v, i) => T): T[] => Array.from({length: l}).map(init);

    private bits: Pixel[];
    private image: Pixel[][];
    private offsets = [[-1, -1], [0, -1], [1, -1], [-1, 0], [0, 0], [1, 0], [-1, 1], [0, 1], [1, 1]];
}

type Pixel = 0 | 1;