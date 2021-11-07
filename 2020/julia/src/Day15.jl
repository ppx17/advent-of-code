module Day15
include("Aoc.jl")
using .Aoc

numbers = parse.(Int64, split(Aoc.input_line(15), ','))

struct SpokenAt
   distance::Int64
   round::Int64
end

# This one uses a bit more memory due to the sometimes unused distance, but is a lot faster
# 5.488538 seconds (179.16 M allocations: 3.951 GiB, 8.05% gc time)
function play_struct(rounds::Int64)
    spoken = Dict{Int64, SpokenAt}()
    last_spoken = 0
    nil_spoken_at = SpokenAt(0, 0)

    for (index, number) in enumerate(numbers)
        spoken[number] = SpokenAt(0, index)
        last_spoken = number
    end

    for round in (length(numbers) + 1):rounds
        last_spoken = get(spoken, last_spoken, nil_spoken_at).distance
        spoken[last_spoken] = SpokenAt(
            round - get(spoken, last_spoken, SpokenAt(0, round)).round,
            round
        )
    end

    last_spoken
end

# This one is a bit cheaper on memory, but the double insert makes it a *lot* slower.
# 7.507847 seconds (145.52 M allocations: 3.141 GiB, 3.48% gc time)
function play_double_dict(rounds::Int64)
    diff = Dict{Int64,Int64}()
    last_spoken_in_round = Dict{Int64,Int64}()
    last_spoken = 0

    for (id, nr) in enumerate(numbers)
        last_spoken_in_round[nr] = id
        last_spoken = nr
    end

    for round in (length(numbers) + 1):rounds
        last_spoken = get(diff, last_spoken, 0)
        diff[last_spoken] = round - get(last_spoken_in_round, last_spoken, round)
        last_spoken_in_round[last_spoken] = round
    end

    last_spoken
end

#@time println("Part 1: ", play_struct(2020));
#@time println("Part 2: ", play_struct(30_000_000));

part1() = play_struct(2020)
part2() = play_struct(30_000_000)

end