module Day3

include("Aoc.jl")
using .Aoc

tomatrix(list) = permutedims(reduce(hcat, list))
toint(a,b) = (a << 1) + b

matrix = tomatrix(map(l -> [parse(Int64, c) for c in l], Aoc.input_lines(3)))

function reducematrix(matrix, decide)
    for x in eachindex(eachcol(matrix))
        size(matrix)[1] == 1 && return matrix

        ones = sum(matrix[:, x])
        zeroes = size(matrix)[1] - ones

        keep = decide(ones, zeroes)

        matrix = tomatrix([matrix[y,:] for y=1:size(matrix)[1] if matrix[y,x] == keep])
    end

    matrix
end

function part1()
    o2 = mapreduce(i -> sum(i)*2>size(matrix)[1], toint, eachcol(matrix));
    o2 * (~o2 & 0b111111111111)
end

function part2()
    o2 = reducematrix(matrix, (ones, zeroes) -> ones >= zeroes ? 1 : 0) |> l -> reduce(toint, l)
    co2 = reducematrix(matrix, (ones, zeroes) -> ones < zeroes ? 1 : 0) |> l -> reduce(toint, l)
    o2 * co2
end

end