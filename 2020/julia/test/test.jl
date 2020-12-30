push!(LOAD_PATH, "../src")

using Test
using Aoc
using Printf

days = vcat(1:6, 9)

@time using Day1, Day3, Day5, Day6, Day8, Day9, Day10, Day12, Day17, Day18, Day22, Day23, Day24, Day25

function format_time(sec::Float64)
    ms = sec * 1000
    if ms < 1
        return @sprintf("% 8.2f µs", ms * 1000)
    end
    return @sprintf("% 8.2f ms", ms)
end
   
header = "Day        Part 1         Part 2           Result 1           Result 2     Valid"
println(header)
println(repeat("-", length(header)))
has_invalids = false
for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        day_module = getfield(Main, day_symbol)
        p1time = @timed day_module.part1()
        p2time = @timed day_module.part2()
        valid = Aoc.validate(day, p1time.value, p2time.value)
        !valid && (global has_invalids = true)
        println(@sprintf("% 2s    %s    %s    % 15s    % 15s        %s", day, format_time(p1time[2]), format_time(p2time[2]), p1time[1], p2time[1], valid ? "✔" : "X"))
    end
end

if has_invalids
    exit(1)
end