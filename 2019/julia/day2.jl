### A Pluto.jl notebook ###
# v0.11.14

using Markdown
using InteractiveUtils

# ╔═╡ b219a80c-08c1-11eb-2d9a-83fd5f61b383
md"""
# Intcode 
"""

# ╔═╡ a007e58a-08b1-11eb-032f-a9f6ad4bc1e9
read_intcode(file) = parse.(Int64, split(readline(file), ","))

# ╔═╡ 5195fbc0-08b2-11eb-3ed5-678009b15d8a
mutable struct Computer
	mem::Vector{Int64}
	ptr::Int64
	Computer(mem) = new(mem, 1)
end

# ╔═╡ 165ded52-08c3-11eb-1dae-4598f8d9ba28
struct Op
	code
	size::Int64
end

# ╔═╡ bea461c4-08b8-11eb-121d-2f1dd04a3180
op!(c::Computer, op) = c.mem[c.mem[c.ptr+3]+1] = op(c.mem[c.mem[c.ptr+1]+1], c.mem[c.mem[c.ptr+2]+1])

# ╔═╡ e9831fb2-08b7-11eb-0dac-fd641d9b42cb
add!(c::Computer) = op!(c, +)

# ╔═╡ 021a0a9a-08b8-11eb-10f4-651454621b2f
mul!(c::Computer) = op!(c, *)

# ╔═╡ b6020af2-08b6-11eb-2cda-1d14832aca9b
operations = Dict(
	1 => Op(add!, 4),
	2 => Op(mul!, 4)
)

# ╔═╡ 26024a8a-08b3-11eb-3305-c125d50100f2
function run_step!(computer::Computer)
	operation = operations[computer.mem[computer.ptr]]
	operation.code(computer)
	computer.ptr += operation.size
	computer
end

# ╔═╡ 87a3ad0c-08b9-11eb-1998-55f2e8bbb176
function run!(computer::Computer)
	while computer.mem[computer.ptr] != 99
		run_step!(computer)
	end
	computer.mem[1]
end

# ╔═╡ 9bcf715a-08c1-11eb-0d27-95b88b8beeff
md"""
# Tests
"""

# ╔═╡ 02e51588-08b7-11eb-3c5a-a3fbfbcb4f89
@assert run!(Computer([1,0,0,0,99])) == 2

# ╔═╡ 05321448-08c2-11eb-1ee4-011d95315112
begin
	example = Computer([1,9,10,3,2,3,11,0,99,30,40,50])
	run_step!(example)
	@assert example.mem == [1,9,10,70,2,3,11,0,99,30,40,50]
	run_step!(example)
	@assert example.mem == [3500,9,10,70,2,3,11,0,99,30,40,50]
end

# ╔═╡ 4a59d60a-08c2-11eb-1cda-1d0b8fc67781
begin
	test1 = Computer([1,0,0,0,99])
	run!(test1)
	@assert test1.mem == [2,0,0,0,99]
end

# ╔═╡ 5dbfc812-08c2-11eb-18da-3972743acc60
begin
	test2 = Computer([2,3,0,3,99])
	run!(test2)
	@assert test2.mem == [2,3,0,6,99]
end

# ╔═╡ 6402b4c0-08c0-11eb-1e26-bbf931197409
begin
	test3 = Computer([2,4,4,5,99,0])
	run!(test3)
	@assert test3.mem ==[2,4,4,5,99,9801]
end

# ╔═╡ 10c6c4cc-08c0-11eb-20cd-2533ba22e672
begin
	test4 = Computer([1,1,1,4,99,5,6,0,99])
	run!(test4)
	@assert test4.mem == [30,1,1,4,2,5,6,0,99]
end

# ╔═╡ 8c1f8720-08c1-11eb-0a01-058d2fba6c2d
md"""
# Part 1
"""

# ╔═╡ a79c8914-08bf-11eb-2103-a1b743080aff
intcode = read_intcode("../input/input-day2.txt")

# ╔═╡ 9c107912-08c5-11eb-3aad-75c9fbe4e0b3
function run(noun, verb)
	code = copy(intcode)
	code[2] = noun
	code[3] = verb
	comp = Computer(code)
	run!(comp)
end

# ╔═╡ b7c816fa-08bf-11eb-0a81-3fec9f10530d
println("Part 1: ", run(12, 2))

# ╔═╡ 89042b70-08c3-11eb-2125-650cdc56945d
md"""
# Part 2
"""

# ╔═╡ 8d80d976-08c3-11eb-0459-0be941af3df9
function p2()
	for noun in 1:100, verb in 1:100
		if run(noun, verb) == 19690720
			return 100noun + verb
		end
	end
end

# ╔═╡ 7aab3990-08c5-11eb-1c24-79863e7bc6a6
println("Part 2: ", p2())

# ╔═╡ Cell order:
# ╟─b219a80c-08c1-11eb-2d9a-83fd5f61b383
# ╠═a007e58a-08b1-11eb-032f-a9f6ad4bc1e9
# ╠═5195fbc0-08b2-11eb-3ed5-678009b15d8a
# ╠═165ded52-08c3-11eb-1dae-4598f8d9ba28
# ╠═e9831fb2-08b7-11eb-0dac-fd641d9b42cb
# ╠═021a0a9a-08b8-11eb-10f4-651454621b2f
# ╠═bea461c4-08b8-11eb-121d-2f1dd04a3180
# ╠═b6020af2-08b6-11eb-2cda-1d14832aca9b
# ╠═26024a8a-08b3-11eb-3305-c125d50100f2
# ╠═87a3ad0c-08b9-11eb-1998-55f2e8bbb176
# ╟─9bcf715a-08c1-11eb-0d27-95b88b8beeff
# ╠═02e51588-08b7-11eb-3c5a-a3fbfbcb4f89
# ╠═05321448-08c2-11eb-1ee4-011d95315112
# ╠═4a59d60a-08c2-11eb-1cda-1d0b8fc67781
# ╠═5dbfc812-08c2-11eb-18da-3972743acc60
# ╠═6402b4c0-08c0-11eb-1e26-bbf931197409
# ╠═10c6c4cc-08c0-11eb-20cd-2533ba22e672
# ╟─8c1f8720-08c1-11eb-0a01-058d2fba6c2d
# ╠═9c107912-08c5-11eb-3aad-75c9fbe4e0b3
# ╠═a79c8914-08bf-11eb-2103-a1b743080aff
# ╠═b7c816fa-08bf-11eb-0a81-3fec9f10530d
# ╟─89042b70-08c3-11eb-2125-650cdc56945d
# ╠═8d80d976-08c3-11eb-0459-0be941af3df9
# ╠═7aab3990-08c5-11eb-1c24-79863e7bc6a6
