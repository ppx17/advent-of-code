module Day17
include("Aoc.jl")
using .Aoc

bitmap = hcat([[c for c in l] for l in split(strip(Aoc.input_string(17)), "\n")]...) .== '#'

function convolute(map, dims)
    old = falses(size(map) .+ 4)
    new = falses(size(map) .+ 2)
    
    offset = CartesianIndex{dims}()
    offset2 = offset*2

    for idx in CartesianIndices(map)
        old[idx + offset2] = map[idx]
    end

    for idx in CartesianIndices(new)
        new[idx] = state(old[idx+offset], sum(old[idx:idx+offset2]) - old[idx+offset])
    end
    new
end

state(active::Bool, neighbors::Int64) = (active && (neighbors == 2 || neighbors == 3)) || (!active && neighbors == 3)

function simulate(dims)
    b = cat(bitmap; dims=dims)
    for cycle in 1:6
        b = convolute(b, dims)
    end
    sum(b)
end

part1() = simulate(3)
part2() = simulate(4)
end
