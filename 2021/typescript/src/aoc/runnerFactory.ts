import {Runner} from "./runner";

export class RunnerFactory {
    public static create(Days: object): Runner {
        const runner = new Runner();

        for (const day in Days) {
            if (typeof Days[day] === "function") {
                runner.register(new Days[day]());
            }
        }

        return runner;
    }
}