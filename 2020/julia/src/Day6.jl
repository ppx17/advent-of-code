module Day6
include("Aoc.jl")
using .Aoc

groups = split(strip(Aoc.input_string(6)), "\n\n")
part1() = mapfoldr(g -> replace(g, "\n" => "")|>unique|>length, +, groups)
part2() = mapfoldr(group -> length(reduce(intersect, [Set(person) for person in split(group, "\n")])), +, groups)
end