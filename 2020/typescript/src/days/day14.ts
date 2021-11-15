import {Day} from "./day";
import {sum} from "./helpers";

export class Day14 extends Day {
    day = (): number => 14;

    part1 = (): string => this.runProgram(this.runInstructionPart1.bind(this))
    part2 = (): string => this.runProgram(this.runInstructionPart2.bind(this))

    private mem: Map<bigint, bigint>;
    private mask: string;

    private runProgram(runInstruction: (address: bigint, param: bigint) => void): string {
        this.mem = new Map<bigint, bigint>();
        this.mask = '';

        this.input
            .map(l => l.split(' = '))
            .forEach((instruction) => instruction[0] === 'mask'
                ? (this.mask = instruction[1])
                : runInstruction(BigInt(instruction[0].match(/mem\[(\d+)]/)[1]), BigInt(instruction[1])));

        return Array.from(this.mem.values()).reduce(sum).toString();
    }

    private runInstructionPart1(address: bigint, param: bigint) {
        this.mem.set(address, Day14.applyMask(this.mask, param));
    }

    private runInstructionPart2(address: bigint, param: bigint) {
        Day14.expandAddress(this.mask, address).forEach(address => this.mem.set(address, BigInt(param)));
    }

    static applyMask(mask: string, input: bigint): bigint {
        const zeroes = BigInt('0b' + mask.replaceAll(/[1|X]/g, '1'));
        const ones = BigInt('0b' + mask.replaceAll('X', '0'));

        return input & zeroes | ones;
    }

    static expandAddress(mask: string, address: bigint): bigint[] {
        const result = [0n];

        mask.split('').forEach((c, pos) => {
            if (c === '1') {
                result.forEach((v, i) => result[i] = (v << 1n) + 1n);
            } else if (c === '0') {
                result.forEach((v, i) => result[i] = (v << 1n) + ((address >> BigInt(mask.length - 1 - pos)) & 1n));
            } else {
                result.forEach((v, i) => {
                    result.push(v << 1n);
                    result[i] = (v << 1n) + 1n;
                });
            }
        });

        return result;
    }
}