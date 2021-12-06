import {Day} from "../aoc";

export class Day23 extends Day {
    day = (): number => 23;
    private inputCups: Cup[];
    private inputMax: number;

    setup() {
        this.inputCups = this.input[0].split('').map((n): Cup => ({label: Number(n)}));
        this.inputMax = Math.max(...this.inputCups.map(c => c.label));
    }

    part1 = (moves: number = 100): string => {
        const firstCup = Day23.play(this.inputCups, moves, this.inputMax);
        return Day23.printCups(firstCup, this.inputCups.length);
    }

    part2 = (moves: number = 10_000_000): string => {
        const max = 1_000_000;

        const extraCups = Array.from({length: max - this.inputMax})
            .map((n, i): Cup => ({label: i + 1 + this.inputMax}))

        const cups = this.inputCups.concat(extraCups);

        const firstCup = Day23.play(cups, moves, max);

        return (firstCup.next.label * firstCup.next.next.label).toString();
    }

    private static play(cups: Cup[], moves: number, max: number): Cup {
        // link & index the cups
        const cupsMap: Map<number, Cup> = new Map();
        cups.forEach((cup, index, array) => {
            cup.next = (array[index + 1] !== undefined)
                ? array[index + 1]
                : array[0];
            cupsMap.set(cup.label, cup);
        });

        let current = cups[0];

        for (let move = 0; move < moves; move++) {
            const pickup = current.next;

            // remove the pickups from the list
            current.next = current.next.next.next.next;

            // select destination label
            let destinationLabel = current.label;
            do {
                destinationLabel = this.clampLabel(destinationLabel - 1, max);
            } while (destinationLabel === pickup.label
            || destinationLabel === pickup.next.label
            || destinationLabel === pickup.next.next.label);

            const destination = cupsMap.get(destinationLabel);

            // Link the pickup after the destination
            const oldNext = destination.next;
            destination.next = pickup;
            pickup.next.next.next = oldNext;

            // pickup a new current cup
            current = current.next;
        }

        return cupsMap.get(1);
    }

    private static clampLabel(label: number, max: number): number {
        return (label < 1) ? max : label;
    }

    private static printCups(current: Cup, numberOfCups: number): string {
        let result = '';
        for (let i = 1; i < numberOfCups; i++) {
            current = current.next;
            result += current.label.toString();
        }
        return result;
    }
}

interface Cup {
    label: number;
    next?: Cup;
}