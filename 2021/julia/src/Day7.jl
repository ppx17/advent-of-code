module Day7
include("Aoc.jl")
using .Aoc
using Statistics

pos = parse.(Int64, split(Aoc.input_line(7), ","))
tri(n) = n * (n+1) ÷ 2
part1() = (t -> mapreduce(i -> abs(t - i), +, pos))(trunc(Int64, median(pos)))
part2() = findmin(t -> mapreduce(i -> tri(abs(t - i)), +, pos), minimum(pos):maximum(pos))[1]
end