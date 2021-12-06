module Day6

include("Aoc.jl")
using .Aoc

numbers = parse.(Int64, split(Aoc.input_line(6), ","))

function evolve(days)
    counts = zeros(Int64, 9)

    for n in numbers
        counts[n] += 1
    end

    for d in 2:days
        b = counts[1]

        for i in 1:8
            counts[i] = counts[i+1]
        end
        counts[7] += b
        counts[9] = b
    end

    sum(counts)
end

part1() = evolve(80)
part2() = evolve(256)

end