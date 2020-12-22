module Day18
include("Aoc.jl")
using .Aoc

function to_rpn(expr::AbstractString, precedence::Bool)
    output::Vector{Union{Char,Int64}} = []
    stack::Vector{Char} = []

    for token in expr
        if token == ' '
            continue
        elseif token == '('
                push!(stack, token)
        elseif token == ')'
            while !isempty(stack)
                op = pop!(stack)
                op == '(' && break
                push!(output, op)
            end
        elseif isdigit(token) # only single digit numbers in input
            push!(output, parse(Int64, token))
        else 
            while !isempty(stack)
                if precedence && stack[end] == '*' && token == '+'
                    break
                end
                if (op = pop!(stack)) == '('
                    push!(stack, op)
                    break
                end
                push!(output, op)
            end
            push!(stack, token)
        end
    end
    while !isempty(stack)
        op = pop!(stack)
        push!(output, op)
    end
    output
end

function solve_rpn(rpn::Vector{Union{Char,Int64}})
    while length(rpn) > 1
        i = findfirst((x) -> typeof(x) == Char, rpn)
        op = popat!(rpn, i)
        a = popat!(rpn, i - 2)
        b = popat!(rpn, i - 2)
        res = op == '*' ? a * b : a + b
        insert!(rpn, i - 2, res)
    end
    rpn[1]
end

input = Aoc.input_lines(18)
solve(expression::AbstractString) = solve_rpn(to_rpn(expression, false))
solve2(expression::AbstractString) = solve_rpn(to_rpn(expression, true))

part1() = mapreduce(solve, +, input)
part2() = mapreduce(solve2, +, input)

end