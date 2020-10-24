using Test

function test_day(day)
    cmd = `julia ../src/day$day.jl`

    result = readlines(cmd)
    expected = readlines("../../expected/day$day.txt")

    @test expected == result
end

for day in 1:7
    @testset "Day $day" begin
        test_day(day)
    end
end