module Day12
include("Aoc.jl")
using .Aoc

struct Instruction
    i::Char
    t::UInt32
end

instructions = [Instruction(x[1], parse(UInt32, x[2:end])) for x in Aoc.input_lines(12)]
wind_directions = Dict('E' => [0, 1], 'W' => [0, -1], 'N' => [-1, 0], 'S' => [1, 0])

rotR(vec::Array{Int64, 1}) = [vec[2], -vec[1]]

function sail(direction, move_dir)
    position = [0, 0]
    for i in instructions
        if i.i == 'L' || i.i == 'R'
            for _ in 1:((i.i == 'L' ? 360 - i.t : i.t) ÷ 90)
                direction = rotR(direction)
            end
        elseif i.i == 'F'
            position += direction * i.t
        else
            move_dir && (direction += wind_directions[i.i] * i.t)
            !move_dir && (position += wind_directions[i.i] * i.t)
        end
    end
    sum(abs.(position))
end

part1() = sail([0, 1], false)
part2() = sail([-1, 10], true)
end