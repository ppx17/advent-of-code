push!(LOAD_PATH, "../src")

using Test
using Aoc
using Printf

days = vcat(1:6, 9)

@time using Day1, Day3, Day5, Day6, Day9, Day12, Day18, Day23

for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        @testset "Day $day" begin
            day_module = getfield(Main, day_symbol)
            @test Aoc.validate(day, day_module.part1(), day_module.part2())
        end
    end
end

function format_time(sec::Float64)
    ms = sec * 1000
    if ms < 1
        return @sprintf("% 8.2f µs", ms * 1000)
    end
    return @sprintf("% 8.2f ms", ms)
end
   


println("Day\t  Part 1\t  Part 2\t       Result 1\t       Result 2")
println(repeat("-", 71))
for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        day_module = getfield(Main, day_symbol)
        p1time = @timed day_module.part1()
        p2time = @timed day_module.part2()
        println(@sprintf("%s\t%s\t%s\t% 15s\t% 15s", day, format_time(p1time[2]), format_time(p2time[2]), p1time[1], p2time[1]))
    end
end
