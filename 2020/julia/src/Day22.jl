module Day22
include("Aoc.jl")
using .Aoc

lines = Aoc.input_lines(22)[2:end]

p2 = findfirst(isequal("Player 2:"), lines)

stack1 = parse.(Int64, lines[1:p2-2])
stack2 = parse.(Int64, lines[p2+1:end])

score(stack::Vector{Int64}) = reduce(+, [i * n for (i,n) in enumerate(reverse(stack))])

function combat(p1::Vector{Int64}, p2::Vector{Int64}, recurse::Bool, sub::Bool)
    seen = Dict{Tuple{Int64,Int64},Bool}()
    while !isempty(p1) && !isempty(p2)
        cur_stacks = (score(p1), score(p2))
        haskey(seen, cur_stacks) && return "p1"
        seen[cur_stacks] = true

        c1 = popat!(p1, 1)
        c2 = popat!(p2, 1)

        if recurse && length(p1) >= c1 && length(p2) >= c2
            if combat(p1[1:c1], p2[1:c2], true, true) == "p1"
                push!(p1, c1)
                push!(p1, c2)
            else
                push!(p2, c2)
                push!(p2, c1)
            end
            continue
        end

        if c1 > c2
            push!(p1, c1)
            push!(p1, c2)
        else
            push!(p2, c2)
            push!(p2, c1)
        end
    end

    sub && return isempty(p1) ? "p2" : "p1"
    score(isempty(p1) ? p2 : p1)
end

part1() = combat(copy(stack1), copy(stack2), false, false)
part2() = combat(copy(stack1), copy(stack2), true, false)
end
