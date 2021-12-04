push!(LOAD_PATH, "../src")

using Test
using Aoc
using Printf

days = vcat(1:1)

@time using Day1, Day2, Day3

function format_time(sec::Float64)
    ms = sec * 1000
    if ms < 1
        return @sprintf("% 8.2f µs", ms * 1000)
    end
    if ms < 1000
        return @sprintf("% 8.2f ms", ms)
    end
    return @sprintf("\x1b[31m% 8.2f ms\x1b[0m", ms)
end
   
header = "│ Day │   │      Part 1 │      Part 2 │          Result 1 │         Result 2 │"

println(repeat("─", length(header)))
println(header)
println(repeat("─", length(header)))
has_invalids = false
for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        day_module = getfield(Main, day_symbol)
        p1time = @timed day_module.part1()
        p2time = @timed day_module.part2()
        valid = Aoc.validate(day, p1time.value, p2time.value)
        !valid && (global has_invalids = true)
        println(@sprintf("│ % 2s  │ %s │ %s │ %s │   % 15s │  % 15s │",
            day,
            valid ? "\x1b[32m✔\x1b[0m" : "\x1b[31mX\x1b[0m",
            format_time(p1time[2]),
            format_time(p2time[2]),
            p1time[1],
            p2time[1]
        ))
    end
end
println(repeat("─", length(header)))

if has_invalids
    exit(1)
end