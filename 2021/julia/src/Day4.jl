
include("Aoc.jl")
using .Aoc

input = Aoc.input_lines(4)
numbers = parse.(Int64, split(popfirst!(input), ','))

input |> join