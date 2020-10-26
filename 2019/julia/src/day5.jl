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

println("Part 1: ", part1())
println("Part 2: ", part2())