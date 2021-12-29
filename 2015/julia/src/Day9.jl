module Day9

include("Aoc.jl")
using .Aoc

legs = [match(r"([a-zA-Z]+) to ([a-zA-Z]+) = ([0-9]+)", l) for l in Aoc.input_lines(9)]

cities = unique(vcat(map(m -> m.captures[1], legs), map(m -> m.captures[2], legs)))
cityindices = Set(1:length(cities))

distances = zeros(Int64, length(cities), length(cities))

cityidx(name) = findfirst(c -> c == name, cities)

for leg in legs
    srcidx = cityidx(leg.captures[1])
    dstidx = cityidx(leg.captures[2])
    distances[[CartesianIndex(srcidx,dstidx), CartesianIndex(dstidx,srcidx)]] .= parse(Int64, leg.captures[3])
end

struct Route
    length::Int64
    visited::Set{Int64}
    last::Int64
end

select(selector, routes) = selector(r -> r.length, routes)

function visit(route::Route, selector)
    tovisit = setdiff(cityindices, route.visited)

    isempty(tovisit) && return route

    routes = [visit(Route(route.length + distances[CartesianIndex(route.last, i)], push!(copy(route.visited), i), i), selector) for i in tovisit]

    routes[select(selector, routes)[2]]
end

run(selector) = select(selector, [visit(Route(0, Set([i]), i), selector) for i in cityindices])[1]

part1() = run(findmin)
part2() = run(findmax)

end