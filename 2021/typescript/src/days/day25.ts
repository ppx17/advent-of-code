import {Day} from "../aoc";

export class Day25 extends Day {
    day = (): number => 25;

    part1 = () => this.stepToEnd(this.map)
    part2 = () => '-'

    setup = () => {
        this.map = this.input.map(l => l.split(''));
        this.width = this.map[0].length;
        this.height = this.map.length;
    };

    private stepEast(map: string[][]): string[][] {
        const result = this.newMap();

        for(let y = 0; y < this.height; y++) {
            for(let x = 0; x < this.width; x++) {
                if(map[y][x] === '>') {
                    if(map[y][(x+1)%this.width] === '.') {
                        result[y][(x+1)%this.width] = '>';
                    }else{
                        result[y][x] = '>';
                    }
                }else if(map[y][x] === 'v') {
                    result[y][x] = 'v';
                }
            }
        }

        return result;
    }

    private stepSouth(map: string[][]): string[][] {
        const result = this.newMap();

        for(let y = 0; y < this.height; y++) {
            for(let x = 0; x < this.width; x++) {
                if(map[y][x] === 'v') {
                    if(map[(y+1)%this.height][x] === '.') {
                        result[(y+1)%this.height][x] = 'v';
                    }else{
                        result[y][x] = 'v';
                    }
                }else if(map[y][x] === '>') {
                    result[y][x] = '>';
                }
            }
        }

        return result;
    }

    private stepToEnd(map: string[][]): number {
        let rounds = 1;

        for(let last = Day25.serialize(map); true; rounds++) {
            map = this.stepEast(map);
            map = this.stepSouth(map);

            if (Day25.serialize(map) === last) {
                break;
            }

            last = Day25.serialize(map);
        }

        return rounds;
    }

    private static serialize = (map: string[][]): string => map.map(l => l.join('')).join('');
    private newMap = () => Array.from({length: this.height}).map(r => Array.from({length: this.width}).map(() => '.'));

    private map: string[][];
    private width: number;

    private height: number;
}