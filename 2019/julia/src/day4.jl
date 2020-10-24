include("Aoc.jl")
using .Aoc


bounds = parse.(Int64, split(Aoc.input_line(4), "-"))

candidates = bounds[1]:bounds[2]
pw_len = length(string(bounds[1]))
part1 = 0
part2 = 0

patterns = [repeat(string(i), 2) for i in 0:9]
antipatterns = [repeat(string(i), 3) for i in 0:9]

function is_increasing(val)
    for i in 1:(length(val) - 1)
        if val[i] > val[i + 1]
            return false
        end
    end
    return true
end

for candidate in candidates
    s_candidate = string(candidate)

    if ! is_increasing(s_candidate)
        continue
    end
    
    double = false
    triple = false
    for (pattern, antipattern) in zip(patterns, antipatterns)
        if occursin(pattern, s_candidate)
            double = true
            if !occursin(antipattern, s_candidate)
                triple = true
                break;
            end
        end
    end

    global part1 += double
    global part2 += triple
end

println("Part 1: ", part1)
println("Part 2: ", part2)
