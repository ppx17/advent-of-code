module Day4

include("Aoc.jl")
using .Aoc

mutable struct Board
    square::Array{Int64}
    called::BitArray{2}
    done::Bool
    Board(square) = new(square, square .== -1)
end

input = Aoc.input_lines(4)
numbers = parse.(Int64, split(popfirst!(input), ','))
boards = input |>
    s -> strip(join(s, "\n")) |>
    s -> split(s, "\n\n") |>
    all -> map(board -> split.(split(board, "\n")), all) |>
    all -> map(board -> map(row -> parse.(Int64, row), board), all) |>
    all -> map(board -> permutedims(reduce(hcat, board)), all) |>
    all -> map(board -> Board(board), all)

call!(board::Board, number) = board.called = board.called .|| board.square .== number
score(board::Board, number) = sum(board.square .* (board.called .== 0)) * number
hasbingo(board::Board) = findfirst(r -> sum(r) == size(board.square, 1), [eachrow(board.called)..., eachcol(board.called)...]) != nothing

function part1()
    for n in numbers, b in boards
        call!(b, n)
        hasbingo(b) && return score(b, n)
    end
end

function part2()
    for n in numbers, b in boards
        b.done && continue
        call!(b, n)

        if hasbingo(b)
            count(b -> !b.done, boards) == 1 && return score(b, n)
            b.done = true
        end
    end
end

end