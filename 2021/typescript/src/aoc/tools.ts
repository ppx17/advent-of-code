import * as fs from "fs";

export class Tools {
    static inputString(day: number): string {
        const path = `../../input/input-day${day}.txt`;
        return fs.readFileSync(path, 'utf8').trim();
    }

    static input(day: number): string[] {
        return this.inputString(day).split(/\r?\n/);
    }

    static expected(day: number, part: number): null | string {
        const path = `../../expected/day${day}.txt`;

        if( ! fs.existsSync(path)) return null;

        const lines = fs.readFileSync(path, 'utf8').split(/\r?\n/);

        return lines.find((line) => line.startsWith(`Part ${part}: `))?.split(': ', 2)[1] ?? null;
    }
}