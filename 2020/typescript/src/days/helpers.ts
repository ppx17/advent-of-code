export class Vector {
    public readonly values: number[];

    constructor(...values: number[]) {
        this.values = values;
    }

    get x(): number {
        return this.values[0] ?? 0;
    }

    get y(): number {
        return this.values[1] ?? 0;
    }

    get z(): number {
        return this.values[2] ?? 0;
    }

    get dimensions(): number {
        return this.values.length;
    }

    add(other: Vector): Vector {
        return new Vector(...this.values.map((v, i) => v + other.values[i] ?? 0));
    }

    within(boundary: Vector): boolean {
        for (let i = 0; i < boundary.dimensions; i++) {
            const b = boundary.values[i];
            if (b >= 0) {
                if (this.values[i] < 0 || this.values[i] > b) {
                    return false;
                }
            } else {
                if (this.values[i] > 0 || this.values[i] < b) {
                    return false;
                }
            }
        }

        return true;
    }
}