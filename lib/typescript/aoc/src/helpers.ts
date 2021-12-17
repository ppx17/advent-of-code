export class Vector {
    public readonly values: number[];

    public static zero(dimensions: number = 2): Vector {
        return new this(...Array.from({length: dimensions}).map(() => 0));
    }

    public static one(dimensions: number = 2): Vector {
        return new this(...Array.from({length: dimensions}).map(() => 1));
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

    dimension(dimension: number): number {
        return this.values[dimension];
    }

    add(other: Vector): Vector {
        return new Vector(...this.values.map((v, i) => v + other.values[i] ?? 0));
    }

    sub(other: Vector): Vector {
        return new Vector(...this.values.map((v, i) => v - other.values[i] ?? 0));
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

    between(a: Vector, b: Vector): boolean {
        if (this.dimensions > a.dimensions || this.dimensions > b.dimensions) return false;

        for (let d = 0; d < this.dimensions; d++)
            if (this.values[d] < Math.min(a.values[d], b.values[d])
                || this.values[d] > Math.max(a.values[d], b.values[d]))
                return false;

        return true;
    }

    times(t: number): Vector {
        return new Vector(...this.values.map(v => v * t));
    }

    manhattan(other: Vector): number {
        return this.values
            .map((v: number, i: number) => Math.abs(v - other.values[i] ?? 0))
            .reduce(sum);
    }

    public is(other: Vector): boolean {
        return this.values.length === other.values.length
            && this.values.every((v, i) => v === other.values[i]);
    }

    serialize(): string {
        return this.values.join(':');
    }
}

export function sum(a: number, b: number): number;
export function sum(a: bigint, b: bigint): bigint;
export function sum(a: string, b: string): string;
export function sum(a: any, b: any): any {
    return a + b;
}

export function product(a: number, b: number): number {
    return a * b;
}
