import {Day} from "../aoc";
import {sum} from "../aoc";

export class Day2 extends Day {
  day = (): number => 2;
  part1 = () => this.solve(this.p1)
  part2 = () => this.solve(this.p2)
  private solve = (fn) => String(this.input.map(l => fn(l.charCodeAt(0) - 65, l.charCodeAt(2) - 88)).reduce(sum))
  private p1 = (h, m) => 1 + m + (h === m ? 3 : Number((h + 1) % 3 === m) * 6)
  private p2 = (h, result) => result * 3 + (h + ((result + 2) % 3)) % 3 + 1;
}

/**
 * Approach:
 *
 * First we normalize ABC and XYZ to 012
 *
 * h = he, m = me, both are 0,1 or 2
 *
 * Part 1,
 * - the points for our move are me + 1
 * - the points when draw are 3
 * - If he + 1 mod 3 equals me, means that he was 1 lower (rock below paper, paper below scissors etc), so we won, otherwise we lost
 *   - Cast the 'won' boolean to a number * 6 for the score
 *
 * Part 2,
 *  - We always have the outcome of result, which when multiplied by 3 gives the game score
 *  - To determine what move we make we need to know what offset we want from his move
 *    - The result (lose=0,draw=1,win=2) + 2) % 3 gives us an offset of lose=2,draw=3,win=4.
 *    - Add the offset to his result, and mod by 3 again
 *    - Example, he picks rock (0) and we want to win (2), so we need to pick paper (1); 0 (rock) + 4 (win offset) = 4, mod 3 = 1 (paper)
 *    - Example 2, he picks scissors (2) and we want to lose (0), so we need to pick paper (1); 2 (scissors) + 2 (lose offset) = 4 mod 3 = 1(paper)
 *    - Then we need to add the 1 offset for our choice.
 */