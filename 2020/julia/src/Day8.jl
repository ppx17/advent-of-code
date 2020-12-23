module Day8
include("Aoc.jl")
using .Aoc

instructions = map((x) -> (x[1], parse(Int64, x[2])), split.(Aoc.input_lines(8)))

function run(instructions)
    seen = Dict{Int64,Bool}()
    incount = length(instructions)
    acc = ptr = 0

    while !haskey(seen, ptr)
        seen[ptr] = true
        (op, param) = instructions[ptr + 1]
        if op == "nop"
            ptr += 1
        elseif op == "acc"
            acc += param
            ptr += 1
        elseif op == "jmp"
            ptr += param
        end
        if ptr == incount
            return (true, acc)
        end
    end
    (false, acc)
end

part1() = run(instructions)[2]

function part2()
    for (idx, (op, param)) in enumerate(instructions)
        op == "acc" && continue
        instr = copy(instructions)
        instr[idx] = (op == "jmp" ? "nop" : "jmp", param)
        (exit, acc) = run(instr)
        exit && return acc
    end
end
end
