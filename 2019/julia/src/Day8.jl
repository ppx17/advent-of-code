module Day8

include("Aoc.jl")
using .Aoc

data = Aoc.input_line(8)

width = 25
height = 6

numbers = parse.(Int64, split(data, ""))

layers = mapslices(rotl90, reverse(reshape(numbers, (width, height, :)), dims=2), dims=[1,2])

instances(v, f) = sum([e == f for e in v])

function part1()
    zero_count = mapslices((l) -> instances(l, 0), layers, dims=[1,2])
    layer_index = findmin(zero_count)[2][3]
    layer = layers[:, :, layer_index]
    instances(layer, 1) * instances(layer, 2)
end

color(v) = [" ", "#"][first(filter(c -> c < 2, v)) + 1]

function part2()
    image = fill(" ", (height, width))
    for x in 1:width, y in 1:height
        image[y,x] = color(layers[y, x, :])
    end
    [join(image[y, :]) for y in 1:height]
end

export part1, part2
end