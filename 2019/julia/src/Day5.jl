module Day5

include("Aoc.jl")
using .Aoc
include("Intcode.jl")
using .Intcode

intcode = Aoc.intcode(5)

function run(input)
    computer = Computer(copy(intcode))
    computer.input = [input]
    Intcode.run!(computer)
    computer.output
end

part1() = run(1)
part2() = run(5)

export part1, part2
end