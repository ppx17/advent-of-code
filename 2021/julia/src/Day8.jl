include("Aoc.jl")
using .Aoc

entries = map.(p -> split(p), split.(Aoc.input_lines(8), " | "))
part1() = sum(map(e -> count(x -> x ∉ 5:6, length.(e[2])), entries))

struct Digit
    text::Set{string}
    number::Int64
    Digit(text) = new(Set(split(text, "")))
end

function resolve(patterns)
    p2n = Dict{String, Int64}()
    n2p = Dict{Int64, String}()
    foreach(t -> begin
        p = patterns[findfirst(o -> length(o) == t[1], patterns)]
        p2n[p] = t[2]
        n2p[t[2]] = p
    end, [(2,1),(3,7),(4,4),(7,8)])
    deleteat!(patterns, map(x -> length(x) ∉ 5:6, patterns))

    three = patterns[findfirst(o -> length(o) == 5 && contains(o, n2p[1]))]
    println(three)

end


# resolve(["acedgfb","cdfbe","gcdfa","fbcad","dab","cefabd","cdfgeb","eafb","cagedb","ab"])

digit = Digit("acedgfb")

println(digit)