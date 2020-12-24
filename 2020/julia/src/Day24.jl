module Day24
include("Aoc.jl")
using .Aoc

directions = Dict(
    "ne" => CartesianIndex(-1, 1),
    "e" => CartesianIndex(0, 2),
    "se" => CartesianIndex(1, 1),
    "sw" => CartesianIndex(1, -1),
    "w" => CartesianIndex(0, -2),
    "nw" => CartesianIndex(-1, -1)
)

dirlist = values(directions)

function makegrid(instructions::Vector{String})
    grid = Dict{CartesianIndex, Bool}()
    for instruction in instructions
        pos = CartesianIndex(0, 0)
        a = nothing
        for l in instruction
            if l == 'n' || l == 's'
                a = l
                continue
            end
            if a != nothing
                l = a * l
                a = nothing
            end
            pos += directions[string(l)]
        end
        grid[pos] = !get(grid, pos, false)
    end
    grid
end

function iterate(grid::Dict{CartesianIndex, Bool})
    step = CartesianIndex(1,2)
    new = Dict{CartesianIndex, Bool}()
    (min, max) = extrema(keys(grid))
    min -= step
    max += step
    for y in min[1]:1:max[1], x in min[2]:2:max[2]
        p = CartesianIndex(y, x + (1&y))
        new[p] = newcolor(grid, p)
    end
    new
end

neighbors(pos::CartesianIndex) = map(x -> x + pos, dirlist)

function newcolor(grid::Dict{CartesianIndex, Bool}, pos::CartesianIndex)
   bc = sum(map(n -> get(grid, n, false), neighbors(pos)))
   get(grid, pos, false) ? bc == 1 || bc == 2 : bc == 2
end

grid = makegrid(Aoc.input_lines(24))

part1() = sum(values(grid))

function part2()
    g = grid
    for d in 1:100
        g = iterate(g)
    end
    sum(values(g))
end
end
