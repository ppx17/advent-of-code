import {Day} from "./day";

export class Day15 extends Day {
    day = (): number => 15;

    part1 = (): string => this.play(2020);

    part2 = (): string => this.play(30_000_000);

    play = (rounds: number = 2020): string => {
        const spoken: Map<number, SpokenAt> = new Map();

        const numbers = this.input[0].split(',').map(n => parseInt(n));
        let lastSpoken: number = 0;

        numbers.forEach((n, i) => {
            spoken.set(n, {distance: null, round: i + 1});
            lastSpoken = n;
        });

        for (let round = numbers.length + 1; round <= rounds; round++) {
            lastSpoken = spoken.get(lastSpoken).distance ?? 0;
            spoken.set(lastSpoken, {
                distance: spoken.has(lastSpoken) ? round - spoken.get(lastSpoken).round : null,
                round
            });
        }

        return lastSpoken.toString();
    }
}

interface SpokenAt {
    distance: number | null,
    round: number,
}
