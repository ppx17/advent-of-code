module Day5
include("Aoc.jl")
using .Aoc

passes = Aoc.input_lines(5)
tr = Dict('F' => 0, 'L' => 0, 'B' => 1, 'R' => 1)

seatId(pass) = parse(Int64, join([tr[c] for c in pass]), base=2)
ids = seatId.(passes)

part1() = maximum(ids)
part2() = filter(id -> id ∉ ids && id-1 ∈ ids && id+1 ∈ ids, minimum(ids):maximum(ids))[1]
end