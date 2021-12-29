module Day1

include("Aoc.jl")
using .Aoc

d = parse.(Int64, Aoc.input_lines(1))
part1() = count(i -> i > 1 && d[i] > d[i-1], eachindex(d))
part2() = count(i -> i > 3 && sum(d[i-2:i]) > sum(d[i-3:i-1]), eachindex(d))

end