import numpy as np

with open('../input/input-day1.txt', 'r') as content_file:
    instructions = content_file.readline().split(', ')
    
    
position = np.array([0, 0])
direction = np.array([0, -1])

def turnLeft(direction):
    if direction[0] == 0:
        direction[0] = direction[1]
        direction[1] = 0
    else:
        direction[1] = -direction[0]
        direction[0] = 0
    return direction
    
def turnRight(direction):
    if direction[0] == 0:
        direction[0] = -direction[1]
        direction[1] = 0
    else:
        direction[1] = direction[0]
        direction[0] = 0
    return direction
    
visited = []
part2 = None
for instruction in instructions:
    if instruction[0] == 'R':
        direction = turnRight(direction)
    else:
        direction = turnLeft(direction)
    
    for i in range(int(instruction[1:])):
        position += direction
        key = "{}:{}".format(position[0], position[1])
        if part2 is None and key in visited:
            part2 = position.copy()
        visited.append(key)
    
print("Part 1: " + str(abs(position[0]) + abs(position[1])))
print("Part 2: " + str(abs(part2[0]) + abs(part2[1])))
