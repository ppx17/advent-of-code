push!(LOAD_PATH, "../src")

using Test
using Aoc
using Printf

days = vcat(1:6, 9)

@time using Day1, Day3, Day5, Day6, Day8, Day9, Day10, Day12, Day17, Day18, Day22, Day23, Day24, Day25

# for day in 1:25
#     day_symbol = Symbol("Day", day)
#     if isdefined(Main, day_symbol)
#         @testset "Day $day" begin
#             day_module = getfield(Main, day_symbol)
#             @test Aoc.validate(day, day_module.part1(), day_module.part2())
#         end
#     end
# end

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
for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        day_module = getfield(Main, day_symbol)
        p1time = @timed day_module.part1()
        p2time = @timed day_module.part2()
        correct = Aoc.validate(day, p1time.value, p2time.value) ? "✔" : "X"
        println(@sprintf("% 2s    %s    %s    % 15s    % 15s        %s", day, format_time(p1time[2]), format_time(p2time[2]), p1time[1], p2time[1], correct))
    end
end
