
include("Aoc.jl")
using .Aoc

input = Aoc.input_lines(4)
numbers = parse.(Int64, split(popfirst!(input), ','))

mutable struct Board
    square::Array{Int64}
    called::BitArray{2}
    Board(square::Array{Int64}) = new(square, square .== -1)
end

boards = input |>
    s -> strip(join(s, "\n")) |>
    s -> split(s, "\n\n") |>
    all -> map(board -> split.(split(board, "\n")), all) |>
    all -> map(board -> map(row -> parse.(Int64, row), board), all) |>
    all -> map(board -> permutedims(reduce(hcat, board)), all) |>
    all -> map(board -> Board(board), all)

call!(board::Board, number::Int64) = board.called = board.called .|| board.square .== number
score(board::Board, number::Int64) = sum(board.square .* (board.called .== 0)) * number
hasbingo(board::Board) = findfirst(r -> sum(r) == size(board.square)[1], [eachrow(board.called)..., eachcol(board.called)...]) != nothing

function part1()
    for num in numbers
        for board in boards
            call!(board, num)
            hasbingo(board) && return score(board, num)
        end
    end
end

function part2()
    for num in numbers
        for (i, b) in enumerate(boards)
            call!(boards, num)
            deleteat!(boards, i)

        if length(boards) == 1
            last = first(boards)
            println(last.square .== num, last, num)
            return score(last, num)
        end
    end
end

println(part1())
println(part2())