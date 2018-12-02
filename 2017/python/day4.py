def has_duplicates(words):
    seen = set()
    for item in words:
        if item in seen:
            return True
        seen.add(item)
    return False

def has_duplicates1(words):
    return any(words.count(w) > 1 for w in words)


part1 = 0
# part2 = 0
with open('../input/input-day4.txt', 'r') as input_file:
    for line in input_file.readlines():
        if not has_duplicates(line.strip().split(' ')):
            part1 += 1

print("Part 1: " + str(part1))

