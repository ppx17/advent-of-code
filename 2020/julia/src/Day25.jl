module Day25
include("Aoc.jl")
using .Aoc

mod = 2020_12_27
(doorspublic, cardspublic) = parse.(Int64, Aoc.input_lines(25))

function part1()
    loop = 0
    while powermod(7, (loop += 1), mod) != cardspublic end
    powermod(doorspublic, loop, mod)
end
part2() = ""
end
