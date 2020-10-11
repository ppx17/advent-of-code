include("Aoc.jl")
using .Aoc
include("Intcode.jl")
using .Intcode


intcode = Aoc.intcode(5)

part1 = Computer(copy(intcode))

part1.input = 1
Intcode.run!(part1)

println("Part 1: ", part1.output)

part2 = Computer(copy(intcode))

part2.input = 5
Intcode.run!(part2)

println("Part 2: ", part2.output)