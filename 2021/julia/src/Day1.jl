module Day1

include("Aoc.jl")
using .Aoc

depth = parse.(Int64, Aoc.input_lines(1))
part1() = count(((i, v),) -> i > 1 && v > depth[i-1], enumerate(depth))
part2() = count(((i, v),) -> i > 3 && sum(depth[i-2:i]) > sum(depth[i-3:i-1]), enumerate(depth))

end