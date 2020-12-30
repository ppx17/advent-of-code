module Day3

include("Aoc.jl")
using .Aoc

map = Aoc.input_lines(3)

width = length(map[1])
height = size(map)[1]

isTree(pos) = map[pos[2]][((pos[1]-1) % width) + 1] == '#'

function treeCount(direction)
    pos = [1, 1]
    trees = 0

    while pos[2] <= height
        trees += isTree(pos) ? 1 : 0
        pos += direction
    end
    
    trees
end

part1() = treeCount([3, 1])
part2() = mapreduce(treeCount, *, eachrow([ 1 1; 3 1; 5 1; 7 1; 1 2 ]))

end