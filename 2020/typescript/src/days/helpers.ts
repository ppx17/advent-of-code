export class Vector {
    public readonly values: number[];

    public static zero(dimensions: number = 2): Vector {
        return new this(...Array.from({length: dimensions}).map(() => 0));
    }

    public static north(): Vector {
        return new this(0, -1);
    }

    public static east(): Vector {
        return new this(1, 0);
    }

    public static south(): Vector {
        return new this(0, 1);
    }

    public static west(): Vector {
        return new this(-1, 0);
    }

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

    times(t: number): Vector {
        return new Vector(...this.values.map(v => v * t));
    }

    manhattan(other: Vector): number {
        return this.values
            .map((v: number, i: number) => Math.abs(v - other.values[i] ?? 0))
            .reduce((x, y) => x + y);
    }
}