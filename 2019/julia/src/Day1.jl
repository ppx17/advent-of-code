module Day1

include("Aoc.jl")
using .Aoc

input = filter(x -> x != "", Aoc.input_lines(1))

fuel(mass) = (mass ÷ 3) - 2

function fuel_recursive(mass)
        need = fuel(mass)
        total = 0
        while need > 0
                total += need
                need = fuel(need)
        end
    total
end

solve(method) = sum(method.(Base.parse.(Int64, input)))

part1() = solve(fuel)
part2() = solve(fuel_recursive)

export part1, part2
end