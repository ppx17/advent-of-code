module Day13
include("Aoc.jl")
using .Aoc

lines = Aoc.input_lines(13)
earliest = parse(Int64, lines[1])
enum = enumerate(split(lines[2], ","))
busses = [parse(Int64, x[2]) for x in enum if x[2] != "x"]
a = [-(x[1] - 1) for x in enum if x[2] != "x"]

function chineseremainder(n::Array, a::Array)
    Π = prod(n)
    mod(sum(ai * invmod(Π ÷ ni, ni) * Π ÷ ni for (ni, ai) in zip(n, a)), Π)
end

part1() = prod(minimum([(b - (earliest % b), b) for b in busses]))
part2() = chineseremainder(busses, a)
end