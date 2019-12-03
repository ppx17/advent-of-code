import numpy as np

from day import Day

class Day3(Day):
    def part1(self):
        return self.solve(self.numbers_from_input())
    
    def part2(self):
        return self.solve(self.numbers_from_input().T.reshape(len(self.input_lines), 3))
    
    def numbers_from_input(self):
        return np.array(list(map(lambda x: x.split(), self.input_lines))).astype(int)
    
    @staticmethod
    def solve(triangle_sides):
        return len(list(filter(lambda x: (x[0] + x[1]) > x[2], map(sorted, triangle_sides))))
        
day = Day3().from_file('../input/input-day3.txt')
print("Part 1: {}".format(day.part1()))
print("Part 2: {}".format(day.part2()))

