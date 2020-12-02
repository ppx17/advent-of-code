push!(LOAD_PATH, "../src")

using Test
using Aoc
@time using Day1, Day2

for day in 1:25
    day_symbol = Symbol("Day", day)
    if isdefined(Main, day_symbol)
        @testset "Day $day" begin
            day_module = getfield(Main, day_symbol)
            @test Aoc.validate(day, day_module.part1(), day_module.part2())
        end
    end
end
