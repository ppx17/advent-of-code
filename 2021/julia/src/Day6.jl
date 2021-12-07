module Day6

include("Aoc.jl")
using .Aoc

numbers = parse.(Int64, split(Aoc.input_line(6), ","))

function evolve(days)
    counts = zeros(Int64, 9)

    foreach(n -> counts[n] += 1, numbers)

    for d in 2:days
        b = counts[1]

        foreach(i -> counts[i] = counts[i+1], 1:8)
        counts[7] += b
        counts[9] = b
    end

    sum(counts)
end

part1() = evolve(80)
part2() = evolve(256)

end