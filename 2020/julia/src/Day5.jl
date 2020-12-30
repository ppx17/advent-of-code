module Day5
include("Aoc.jl")
using .Aoc

tr = Dict('F' => 0, 'L' => 0, 'B' => 1, 'R' => 1)
ids = [parse(Int64, join([tr[c] for c in pass]), base=2) for pass in Aoc.input_lines(5)]

part1() = maximum(ids)
part2() = filter(id -> id ∉ ids && id-1 ∈ ids && id+1 ∈ ids, minimum(ids):maximum(ids))[1]
end