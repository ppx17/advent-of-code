import numpy as np

from day import Day

class Day2(Day):

    def part1(self):
        keypad = np.array([
            [1, 2, 3],
            [4, 5, 6],
            [7, 8, 9]
        ])
    
        return self.navigate_pad(keypad, np.array([1, 1]))

    def part2(self):
        keypad = np.array([
            [None, None, 1, None, None],
            [None, 2, 3, 4, None],
            [5, 6, 7, 8, 9],
            [None, "A", "B", "C", None],
            [None, None, "D", None, None],
        ])
    
        return self.navigate_pad(keypad, np.array([2, 0]))
    
    def navigate_pad(self, keypad, pos):
        directions = {
            "U": np.array([-1, 0]),
            "D": np.array([1, 0]),
            "L": np.array([0, -1]),
            "R": np.array([0, 1])
        }
        
        result = ""
        for instruction in self.input_lines:
            for move in instruction.strip():
                new_pos = pos + directions[move]
                if all(new_pos >= 0) \
                        and all(new_pos < keypad.shape[0]) \
                        and keypad[new_pos[0]][new_pos[1]] is not None:
                    pos = new_pos
            result += str(keypad[pos[0]][pos[1]])
        return result
    
day = Day2().from_file('../input/input-day2.txt')
print("Part 1: {}".format(day.part1()))
print("Part 2: {}".format(day.part2()))