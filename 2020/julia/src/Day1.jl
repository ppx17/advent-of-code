module Day1

include("Aoc.jl")
using .Aoc

amounts = parse.(Int64, Aoc.input_lines(1))
        
function part1()
    for a in amounts, b in amounts
        if a + b == 2020
            return a * b
        end
    end
end

function part2()
    for a in amounts, b in amounts, c in amounts
        if a + b + c == 2020
            return a * b * c
        end
    end
end

end