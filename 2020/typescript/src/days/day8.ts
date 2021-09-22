import {Day} from "./day";

export class Day8 extends Day {
    private instructions: Instruction[] = [];

    day = (): number => 8;

    part1 = (): string => Computer.execute(this.instructions).acc.toString();

    part2(): string {
        for (let i = 0; i < this.instructions.length; i++) {
            if (this.instructions[i].op === "acc") continue;

            const copy = InstructionCopier.copy(this.instructions);
            copy[i].op = copy[i].op === "jmp" ? "nop" : "jmp";

            const res = Computer.execute(copy);
            if (res.ended) return res.acc.toString();
        }
        return 'unknown';
    }

    setup() {
        super.setup();

        this.instructions = this.input
            .map(l => l.split(' '))
            .map(i => ({op: i[0], arg: parseInt(i[1])}));
    }
}

class Computer {
    static execute(instructions: Instruction[]): ExecutionResult {
        const seen = new Set();
        let ptr = 0;
        let acc = 0;

        while (!seen.has(ptr)) {
            seen.add(ptr);

            switch (instructions[ptr].op) {
                case "nop":
                    ptr++;
                    break;
                case "acc":
                    acc += instructions[ptr].arg;
                    ptr++;
                    break;
                case "jmp":
                    ptr += instructions[ptr].arg;
                    break;
            }

            if (ptr >= instructions.length) return {ended: true, acc: acc};
        }

        return {ended: false, acc: acc};
    }
}

class InstructionCopier {
    static copy(instructions: Instruction[]): Instruction[] {
        return instructions.map(i => ({...i}));
    }
}

interface Instruction {
    op: string,
    arg: number,
}

interface ExecutionResult {
    ended: boolean,
    acc: number
}