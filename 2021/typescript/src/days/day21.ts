import {Day} from "../aoc";

export class Day21 extends Day {
    day = (): number => 21;

    part1() {
        const dice = new Dice();
        const players = this.players.map(p => p.clone());

        for (let p = 0; true; p = ++p % 2) {
            players[p].move(dice.roll() + dice.roll() + dice.roll());
            if (players[p].score >= 1000) return dice.rolls * players[++p % 2].score;
        }
    }
    part2() {
        const wins = this.getScore(this.players[0].clone(), this.players[1].clone());
        return Math.max(wins.wins, wins.losses);
    }

    private getScore(me: Player, other: Player): Score {
        if (other.score >= 21) return {wins: 0, losses: 1};
        const score: Score = {wins: 0, losses: 0};

        for (let d of this.dist) {
            const subGameScore = this.getScore(other.clone(), me.clone().move(d.thrown));
            score.wins += subGameScore.losses * d.times;
            score.losses += subGameScore.wins * d.times;
        }

        return score;
    }

    setup = () => this.players = this.input.map(l => new Player(Number(l.split(': ')[1])));

    private players: Player[];
    private dist: Dist[] = [
        {thrown: 3, times: 1},
        {thrown: 4, times: 3},
        {thrown: 5, times: 6},
        {thrown: 6, times: 7},
        {thrown: 7, times: 6},
        {thrown: 8, times: 3},
        {thrown: 9, times: 1},
    ];
}

type Dist = { thrown: number, times: number }
type Score = { wins: number, losses: number }

class Dice {
    state: number = 0;
    rolls: number = 0;

    public roll(): number {
        this.rolls++;
        return this.state = (this.state) % 100 + 1;
    }
}

class Player {
    constructor(public position: number, public score: number = 0) {
    }

    move(steps: number) {
        this.score += (this.position = ((this.position - 1 + steps) % 10) + 1);
        return this;
    }

    clone(): Player {
        return new Player(this.position, this.score);
    }
}