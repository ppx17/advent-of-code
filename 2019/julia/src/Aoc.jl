module Aoc
using Printf
export input_lines, input_line, intcode

input_lines(day::Int64) = readlines("../../input/input-day$day.txt")
input_line(day::Int64) = readline("../../input/input-day$day.txt")
intcode(day::Int64) = parse.(Int64, split(input_line(day), ","))

function validate(day::Int64, part1, part2)
    expected = readlines("../../expected/day$day.txt")  
    result = [@sprintf("Part 1: %s", part1), @sprintf("Part 2: %s", part2)]

    expected == result && return true

    @printf("Expected:\n\n%s\n\nbut got:\n\n%s\n", expected, result)

    false
end
end