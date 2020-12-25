module Day25
include("Aoc.jl")
using .Aoc

mod = 2020_12_27

function findloop(key)
    loop = 1

    while powermod(7, loop, mod) != key
        loop += 1
    end
    loop
end

(doorspublic, cardspublic) = parse.(Int64, Aoc.input_lines(25))

function part1()
    cardloops = findloop(cardspublic)
    powermod(doorspublic, cardloops, mod)
end
part2() = ""
end
