include("Aoc.jl")
using .Aoc
include("Intcode.jl")
using .Intcode

intcode = Aoc.intcode(2)

function run(noun, verb)
	code = copy(intcode)
	code[2] = noun
	code[3] = verb
	comp = Intcode.Computer(code)
	Intcode.run!(comp)
end

println("Part 1: ", run(12, 2))


function p2()
	for noun in 1:100, verb in 1:100
		if run(noun, verb) == 19690720
			return 100noun + verb
		end
	end
end

println("Part 2: ", p2())
