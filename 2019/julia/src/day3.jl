include("Aoc.jl")
using .Aoc
import Base.+

velocities = Dict('U' => (-1, 0), 'D' => (1, 0), 'L' => (0, -1), 'R' => (0, 1))

struct Instruction
    direction::Tuple{Int64,Int64}
    length::Int64
end

parse_instruction(instruction) = Instruction(velocities[instruction[1]], parse(Int64, instruction[2:end]))

routes = [parse_instruction.(split(line, ",")) for line in Aoc.input_lines(3)]

+(a::Tuple{Int64,Int64}, b::Tuple{Int64,Int64}) = (a[1] + b[1], a[2] + b[2])

function walk_route(route)
    position = (0, 0)
    step_count = 0
    seen = Dict{Tuple,Int64}()
    for instruction in route
        for _ in 1:instruction.length
            step_count+=1
            position += instruction.direction
            seen[position] = step_count
        end
    end
    seen
end

positions = walk_route.(routes)
crossings = intersect(keys(positions[1]), keys(positions[2]))

manhattan(position) = sum(abs.(position))

part1() = minimum(manhattan.(crossings))
part2() = minimum([positions[1][crossing] + positions[2][crossing] for crossing in crossings])

println("Part 1: ", part1())
println("Part 2: ", part2())