include("Aoc.jl")
using .Aoc

mutable struct Node
    id::String
    children::Array{Node}
    parent
	Node(id) = new(id, [], nothing)
end

raw = Aoc.input_lines(6)
splitted = map((el) -> split(el, ")"), raw)

node!(tree, id) = haskey(tree, id) ? tree[id] : tree[id] = Node(id)

tree = Dict{String,Node}();
for split in splitted
    parent = node!(tree, split[1])
    child = node!(tree, split[2])

    push!(parent.children, child)
end
for (id, node) in tree
    for child in node.children
        child.parent = node
    end
end

function count_orbits(node, level)
    return (length(node.children) * level) + (length(node.children) === 0 ? 0 : sum([count_orbits(child, level + 1) for child in node.children]))
end

function walkback(node)
    result = []
    while node.parent !== nothing
        push!(result, node.parent.id)
        node = node.parent
    end
    return result
end

part1() = count_orbits(tree["COM"], 1)

function part2()
    santa_path = walkback(tree["SAN"])

    for (y, node) in enumerate(walkback(tree["YOU"]))
        z = findfirst((x) -> x === node, santa_path)
        if z !== nothing
            return y + z - 2 # Account for SAN and YOU themselves
        end
    end
end

println("Part 1: ", part1())
println("Part 2: ", part2())