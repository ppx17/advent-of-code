include("Aoc.jl")
using .Aoc
include("Intcode.jl")
using .Intcode


function calculate_combinations()
    result = Set{Vector{Int64}}()
    for a in 0:4, b in 0:4, c in 0:4, d in 0:4, e in 0:4
        settings = [a,b,c,d,e]
        allunique(settings) || continue
        push!(result, settings)
    end
    result
end

combinations = calculate_combinations()

intcode = Aoc.intcode(7)

function run_combination(combination)
    inputSignal = 0
    for phase in combination
        c = Computer(copy(intcode))
        c.input = [phase, inputSignal]
        Intcode.run!(c)
        inputSignal = c.output
    end
    inputSignal
end

println("Part 1: ", maximum(run_combination.(combinations)))

part2_combinations = [c.+=5 for c in combinations]

function run_combination_recursive(combination)
    computers = Array{Computer,1}()
    for phase in combination
        computer = Computer(copy(intcode))
        push!(computer.input, phase)
        push!(computers, computer)
    end
    
    for (index, computer) = enumerate(computers)
        next_index = index + 1
        next_index > size(computers)[1] && (next_index = 1; true)
        computer.output_callable = (output) -> (push!(computers[next_index].input, output); Intcode.run!(computers[next_index]))
    end

    push!(computers[1].input, 0)
    Intcode.run!(computers[1])

    computers[5].output
end

println("Part 2: ", maximum(run_combination_recursive.(combinations)))