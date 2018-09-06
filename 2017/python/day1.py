with open('../input/input-day1.txt', 'r') as content_file:
    puzzleInput = content_file.read()

part1=0
part2=0
length = len(puzzleInput)

for index, character in enumerate(puzzleInput):
    if character == puzzleInput[(index+1) % length]:
        part1 += int(character)
    
    if character == puzzleInput[(index + int(length/2)) % length]:
        part2 += int(character)

print("Part 1:" + str(part1))
print("Part 2:" + str(part2))