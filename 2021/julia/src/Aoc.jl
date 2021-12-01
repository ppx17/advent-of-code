module Aoc
using Printf
export input_lines, input_line, intcode

function input_lines(day::Int64)
    lines = readlines("../../input/input-day$day.txt")
    return lines[end] == "" ? lines[1:end-1] : lines
end
input_line(day::Int64) = readline("../../input/input-day$day.txt")
input_string(day::Int64) = read("../../input/input-day$day.txt", String)
intcode(day::Int64) = parse.(Int64, split(input_line(day), ","))

function validate(day::Int64, part1, part2)
    expected = readlines("../../expected/day$day.txt")[1:2]

    result = [@sprintf("Part 1: %s", strip(normalize_part(part1))), @sprintf("Part 2: %s", strip(normalize_part(part2)))]

    expected == result && return true

    @printf("Day %s\n-------\nExpected:\n\n%s\n\nbut got:\n\n%s\n", day, expected, result)

    false
end

function normalize_part(part::String)
    part
end

function normalize_part(part::Array{String,1})
    part[1]
end

function normalize_part(part)
    string(part)
end

end