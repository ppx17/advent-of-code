import {Day} from "../aoc";
import {sum} from "../aoc";

export class Day1 extends Day {
  day = (): number => 1;

  part1 = () => String(this.sortedElves()[0])
  part2 = () => String(this.sortedElves().slice(0,3).reduce(sum))

  private sortedElves = () => Array.from(this.groups()).sort((x,y) => y - x);
  private* groups() {
    let elf = 0;
    for(const l of this.input.map(Number)) {
      elf += l;
      if(l === 0) {
        yield elf;
        elf = 0;
      }
    }
    yield elf;
  }
}