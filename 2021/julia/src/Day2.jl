module Day2

include("Aoc.jl")
using .Aoc

tr = Dict("forward" => [1,0], "up" => [0,-1], "down" => [0,1])

instructions = Aoc.input_lines(2)|>
    lines -> map(l -> split(l, ' '), lines)|>
    lines -> map(s -> tr[s[1]] .* parse(Int64, s[2]), lines)

part1() = prod(sum(instructions))

function part2()
    aim, pos = [0,0], [0,0]
    for i in instructions
        i[1] === 0 ? aim += i : pos += aim * i[1] + i
    end
    prod(pos)
end

end