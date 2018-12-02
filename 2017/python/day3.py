import math
from collections import defaultdict

puzzleInput = 361527

ringSize = 0
totalSize = 1
rings = 0

while True:
    ringSize += 8
    totalSize += ringSize
    rings += 1
    if totalSize >= puzzleInput:
        break

sideLength = math.sqrt(totalSize)
offset = totalSize - puzzleInput

if offset < sideLength:
    x = 0  # Bottom row
elif offset < (sideLength * 2) - 1:
    x = sideLength  # Left row
elif offset < (sideLength * 3) - 2:
    x = (sideLength - 1) * 2  # Top row
else:
    x = (sideLength - 1) * 3  # Right row

distance = rings + int(math.fabs((sideLength - 1) / 2 - (offset - x)))

print("Part 1: " + str(distance))

matrix = defaultdict(dict)
matrix[0][0] = 1

currentRing = currentRingSize = currentSideLength = 0

position = (1, 0)

# x,y tuple, up, left, down, right
directions = ((0, -1), (-1, 0), (0, 1), (1, 0))


def sumOfNeighbors(matrix, position):
    sumOfNeighbors = 0
    for modX in range(-1, 1):
        for modY in range(-1, 1):
            col = matrix.get(position[0] + modX)
            if col is not None:
                cell = col.get(position[1] + modY)
                if cell is not None:
                    sumOfNeighbors += cell
    return sumOfNeighbors


while True:
    currentRing += 1
    currentRingSize += 8
    currentSideLength += 2

    for direction in directions:
        position = tuple(map(lambda x, y: x + y, position, direction))
        for i in range(0, currentSideLength):
            latestEntry = sumOfNeighbors(matrix, position)
            print(position)
            print(latestEntry)
            if latestEntry > puzzleInput:
                print("Part 2: " + str(latestEntry))
            matrix[direction[0]][direction[1]] = latestEntry

            position = tuple(map(lambda x, y: x + y, position, direction))

    if currentRing == 2:
        break
