import {Result} from "./runner";
import * as Days from "../../../../2020/typescript/src/days";
import {RunnerFactory} from "./runnerFactory";

const runner = RunnerFactory.create(Days);

export function run(day: number): undefined | Result {
    return runner.day(day) ?? undefined;
}