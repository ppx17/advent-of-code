module Day9
include("Aoc.jl")
using .Aoc

preamble = 25
numbers = parse.(Int64, Aoc.input_lines(9))
count = size(numbers)[1]
weakness = 0

function part1()
    for (idx, nr) in enumerate(numbers[preamble+1:end])
        if ! has_sum(idx + preamble, nr)
            global weakness = nr
            return nr
        end
    end
end

function part2()
    start = 1
    for i in 1:count
        while sum(numbers[start:i]) > weakness && i - start > 2
            start+=1
        end
        
        if sum(numbers[start:i]) == weakness
            return minimum(numbers[start:i]) + maximum(numbers[start:i])
        end
    end
end

function has_sum(index, sum)
    for a = numbers[index-preamble:index], b = numbers[index-preamble+1:index]
        if a + b == sum
            return true
        end
    end
    return false
end

end