import {Result, Runner} from "./runner";
import yargs from "yargs";
import {hideBin} from "yargs/helpers";
import {Renderer} from "./renderer";

export class Cli {
    constructor(private runner: Runner) {

    }

    execute() {
        yargs(hideBin(process.argv))
            .command(['run', 'all'], 'Run all days', this.all.bind(this))
            .command('async', 'Run all days async', this.async.bind(this))
            .command('day <day>', 'Run single day', (yargs) => {
                yargs.positional('day', {
                    type: 'number',
                    demand: true,
                    describe: 'the day to run'
                })
            }, (argv) => this.day(argv.day as number))
            .demandCommand(1)
            .argv
    }

    private all() {
        console.log(Renderer.multiple(this.runner.all()));
    }

    private async() {
        this.runner.allAsync().then((results: Result[]) => {
            console.log(Renderer.multiple(results));
        })
    }

    private day(day: number) {
        const result = this.runner.day(day);
        console.log(Renderer.single(result))
    }
}