module Day23
include("Aoc.jl")
using .Aoc

labels = [parse(Int64, c) for c in Aoc.input_line(23)]

mutable struct Cup
    label::Int64
    next::Union{Nothing,Cup}
    Cup(label::Int64) = new(label, nothing)
end

pick_label(label::Int64, count::Int64) = label > 1 ? label - 1 : count

function play(labels::Array{Int64}, rounds::Int64)
    count = length(labels)
    cups = [Cup(l) for l in labels]
    cups_map = Dict{Int64,Cup}()

    for (i,c) in enumerate(cups)
        ni = i == count ? 1 : i + 1
        c.next = cups[ni]
        cups_map[c.label] = c
    end

    current = cups[1]

    for i in 1:rounds
        one = current.next
        two = one.next
        three = two.next

        after_taken = three.next
        current.next = after_taken

        dest_label = current.label
        dest_label = pick_label(dest_label, count)
        while dest_label == one.label || dest_label == two.label || dest_label == three.label
            dest_label = pick_label(dest_label, count)
        end

        destination = cups_map[dest_label]
        oldNext = destination.next
        destination.next = one
        three.next = oldNext

        current = current.next
    end

    cups_map[1]
end

function part1()
    cup = play(labels, 100)

    result = ""
    for i in 1:8
        cup = cup.next
        result = result * string(cup.label)
    end

    result
end

function part2()
    cup = play(vcat(labels, 10:1_000_000), 10_000_000)
    return cup.next.label * cup.next.next.label
end
end
