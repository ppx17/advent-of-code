module Day2

include("Aoc.jl")
using .Aoc

passwordlist = Aoc.input_string(2)

regex = r"([0-9]+)-([0-9]+) ([a-z]): ([a-z]+)\n"

struct Rule
    min::Int32
    max::Int32
    letter::Char
    password::String

    function Rule(match::RegexMatch)
        (min, max, letter, password) = match.captures
        new(parse(Int32, min), parse(Int32, max), letter[1], password)
    end
end

rules = [Rule(m) for m in eachmatch(regex, passwordlist)]

part1() = count(r -> r.min <= count(c -> c == r.letter, r.password) <= r.max, rules)
part2() = count(r -> (r.password[r.min] == r.letter || r.password[r.max] == r.letter) && r.password[r.min] != r.password[r.max], rules)

end