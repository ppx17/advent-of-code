module Day10
include("Aoc.jl")
using .Aoc

adapters = sort(parse.(Int64, Aoc.input_lines(10)))

function part1()
    diffs = map(t -> t[1] - t[2], zip(adapters, vcat([0], adapters[1:end-1])))
    count(x -> x == 1, diffs) * (count(x -> x == 3, diffs) + 1)
end

function part2()
    routes = Dict{Int64,Int64}(length(adapters) => 1)
    a_map = Dict{Int64,Int64}()
    [a_map[v] = i for (i, v) in enumerate(adapters)]
    for i in Iterators.reverse(1:length(adapters)-1)
        routes[i] = sum(x -> get(routes, get(a_map, get(adapters, i, nothing) + x, 0), 0), 1:3)
    end
    sum(x -> get(routes, x, 0),1:3)
end
end