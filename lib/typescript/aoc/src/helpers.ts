export interface Vec {
    readonly values: number[];

    get x(): number;

    get y(): number;

    get z(): number;

    get dimensions(): number;

    between(a: Vec, b: Vec): boolean;

    within(boundary: Vector): boolean;

    manhattan(other: Vec): number;

    is(other: Vec): boolean;

    serialize(): string;

    add(other: Vec): Vec;

    sub(other: Vec): Vec;

    times(t: number): Vec;

    abs(): Vec;

    rotate3dRightAroundDimension(d: number): Vec;

    rotate3dLeftAroundDimension(d: number): Vec;

    rotateAroundX(): Vec;

    rotateAroundY(): Vec;

    rotateAroundZ(): Vec;

    mutable(): MutableVector;

    fixed(): Vector;
}

abstract class BaseVector {
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

    between(a: Vec, b: Vec): boolean {
        if (this.dimensions > a.dimensions || this.dimensions > b.dimensions) return false;

        for (let d = 0; d < this.dimensions; d++)
            if (this.values[d] < Math.min(a.values[d], b.values[d])
                || this.values[d] > Math.max(a.values[d], b.values[d]))
                return false;

        return true;
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

    manhattan(other: Vec): number {
        return this.values
            .map((v: number, i: number) => Math.abs(v - other.values[i] ?? 0))
            .reduce(sum);
    }

    abstract rotate3dLeftAroundDimension(d: number): Vec;

    abstract rotate3dRightAroundDimension(d: number): Vec;

    rotateAroundX(): Vec {
        return this.rotate3dRightAroundDimension(0);
    }

    rotateAroundY(): Vec {
        return this.rotate3dRightAroundDimension(1);
    }

    rotateAroundZ(): Vec {
        return this.rotate3dRightAroundDimension(2);
    }

    is(other: Vec): boolean {
        return this.values.length === other.values.length
            && this.values.every((v, i) => v === other.values[i]);
    }

    serialize(): string {
        return this.values.join(':');
    }
}

export class Vector extends BaseVector implements Vec {
    private static _north: Vector;
    private static _south: Vector;
    private static _east: Vector;
    private static _west: Vector;
    public static zero(dimensions: number = 2): Vector {
        return new this(...Array.from({length: dimensions}).map(() => 0));
    }

    public static one(dimensions: number = 2): Vector {
        return new this(...Array.from({length: dimensions}).map(() => 1));
    }

    public static north(): Vector {
        return this._north ??= new this(0, -1);
    }

    public static east(): Vector {
        return this._east ??= new this(1, 0);
    }

    public static south(): Vector {
        return this._south ??= new this(0, 1);
    }

    public static west(): Vector {
        return this._west ??= new this(-1, 0);
    }

    add(other: Vec): Vector {
        return new Vector(...this.values.map((v, i) => v + other.values[i] ?? 0));
    }

    sub(other: Vec): Vector {
        return new Vector(...this.values.map((v, i) => v - other.values[i] ?? 0));
    }

    times(t: number): Vector {
        return new Vector(...this.values.map(v => v * t));
    }

    abs(): Vector {
        return new Vector(...this.values.map((v) => Math.abs(v)));
    }

    rotate3dRightAroundDimension(d: number): Vec {
        const rotated = [];
        const a = (d + 1) % 3;
        const b = (d + 2) % 3;
        rotated[d] = this.values[d];
        rotated[a] = -this.values[b];
        rotated[b] = this.values[a];
        return new Vector(...rotated);
    }

    rotate3dLeftAroundDimension(d: number): Vec {
        const rotated = [];
        const a = (d + 1) % 3;
        const b = (d + 2) % 3;
        rotated[d] = this.values[d];
        rotated[a] = this.values[b];
        rotated[b] = -this.values[a];
        return new Vector(...rotated);
    }

    rotateAroundX(): Vec {
        return new Vector(this.x, -this.z, this.y);
    }

    rotateAroundY(): Vec {
        return new Vector(-this.z, this.y, this.x);
    }

    rotateAroundZ(): Vec {
        return new Vector(-this.y, this.x, this.z);
    }

    static deserialize(key: string) {
        return new Vector(...key.split(':').map(Number));
    }

    mutable(): MutableVector { return new MutableVector(...this.values) };
    fixed(): Vector {return this; }
}

class MutableVector extends BaseVector implements Vec {
    abs(): Vec {
        for (let i = 0; i < this.dimensions; i++)
            this.values[i] = Math.abs(this.values[i]);
        return this;
    }

    add(other: Vec): MutableVector {
        for (let i = 0; i < this.dimensions; i++)
            this.values[i] += other.values[i] ?? 0;
        return this;
    }

    sub(other: Vec): MutableVector {
        for (let i = 0; i < this.dimensions; i++)
            this.values[i] -= other.values[i] ?? 0;
        return this;
    }

    times(t: number): MutableVector {
        for (let i = 0; i < this.dimensions; i++)
            this.values[i] *= t;
        return this;
    }

    rotate3dLeftAroundDimension(d: number): Vec {
        const a = (d + 1) % 3;
        const b = (d + 2) % 3;
        const keep = this.values[a];
        this.values[a] = this.values[b];
        this.values[b] = -keep;
        return this;
    }

    rotate3dRightAroundDimension(d: number): Vec {
        const a = (++d) % 3;
        const b = (++d) % 3;
        const keep = this.values[a];
        this.values[a] = -this.values[b];
        this.values[b] = keep;
        return this;
    }

    fixed = (): Vector => new Vector(...this.values);
    mutable = (): MutableVector => this;
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
