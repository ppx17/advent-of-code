module Day2

include("Aoc.jl")
using .Aoc

instructions = Aoc.input_lines(2)|>
  t -> split.(t, ' ')|>
  t -> map.(x -> Int(only(x)), t)|>
  t -> map(l -> l - [65,88], t)

p1 = (h, m) -> 1 + m + (h === m ? 3 : ((h + 1) % 3 === m) * 6)
p2 = (h, r) -> r * 3 + (h + ((r + 2) % 3)) % 3 + 1

solve = fn -> map(i -> fn(i[1], i[2]), instructions)|>sum
part1 = () -> solve(p1)
part2= () -> solve(p2)

end

