import {Judge, Result} from "./runner";
import * as Days from "./days";
import {Day} from "./days/day";

const dayMap = new Map<number, Day>();
for (const day in Days) {
    if (typeof Days[day] === "function") {
        const d = new Days[day]();
        dayMap.set(d.day(), d);
    }
}

export function run(day: number): undefined | Result {
    const instance = dayMap.get(day);

    if (instance === undefined) {
        return;
    }

    return Judge.judge(instance);
}