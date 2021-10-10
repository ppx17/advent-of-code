import {Day} from "./day";

export class Day14 extends Day {
    day = (): number => 14;

    part1 = (): string =>
        this.run((mem, mask, address, param) =>
            mem.set(address, this.applyMask(mask, param)))
    part2 = (): string =>
        this.run((mem, mask, address, param) =>
            this.expandAddress(mask, address).forEach(address => mem.set(address, BigInt(param))))

    private run(execute: (mem: Map<bigint, bigint>, mask: string, address: bigint, param: bigint) => void): string {
        const mem = new Map<bigint, bigint>();
        let mask: string = '';

        this.input
            .map(l => l.split(' = '))
            .forEach((instruction) => instruction[0] === 'mask'
                ? (mask = instruction[1])
                : execute(mem, mask, BigInt(instruction[0].match(/mem\[(\d+)]/)[1]), BigInt(instruction[1])));

        return Array.from(mem.values()).reduce((a, b) => a + b).toString();
    }

    applyMask(mask: string, input: bigint): bigint {
        const zeroes = BigInt('0b' + mask.replaceAll(/[1|X]/g, '1'));
        const ones = BigInt('0b' + mask.replaceAll('X', '0'));

        return input & zeroes | ones;
    }

    expandAddress(mask: string, address: bigint): bigint[] {
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