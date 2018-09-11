import numpy as np

with open('../input/input-day2.txt', 'r') as input_file:
    part1 = 0
    part2 = 0
    for line in input_file.readlines():
        numbers = [int(x) for x in line.split('\t')]
        part1 += np.max(numbers) - np.min(numbers)

        for x in numbers:
            for y in numbers:
                if x == y: continue
                if x % y == 0:
                    part2 += int(x / y)

print("Part 1: " + str(part1))
print("Part 2: " + str(part2))
