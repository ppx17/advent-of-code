module Aoc
    export input_lines, input_line, intcode

    input_lines(day::Int64) = readlines("../../input/input-day$day.txt")
    input_line(day::Int64) = readline("../../input/input-day$day.txt")
    intcode(day::Int64) = parse.(Int64, split(input_line(day), ","))
end