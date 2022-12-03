import { Day, desc } from "../aoc";
import {sum} from "../aoc";

export class Day1 extends Day {
  day = (): number => 1;

  part1 = () => String(this.sortedElves()[0])
  part2 = () => String(this.sortedElves().slice(0,3).reduce(sum))

  private sortedElves = () => Array.from(this.groups()).sort(desc);
  private* groups() {
    for(let i = 0, x = this.input; i < x.length; i = x.indexOf('0', i))
      yield x.slice(i, x.indexOf('0', i)).map(Number).reduce(sum)
  }
}