module Intcode
export run!, Computer
include("Aoc.jl")
using .Aoc

struct Op
	code
	size::Int64
end

mutable struct Computer
	mem::Vector{Int64}
	ptr::Int64
	input::Int64
	output::Int64
	Computer(mem) = new(mem, 1)
end

struct Instruction
	operation::Op
	param_a::Int64
	param_b::Int64
end

function instruction(c::Computer)
	raw_op = c.mem[c.ptr]
	op_code = raw_op % 100
	op = operations[op_code]
	Instruction(
		op,
		op.size >= 2 ? ((raw_op % 1000)  ÷ 100  == 0 ? read_pos(c, 1) : read_val(c, 1)) : 0,
		op.size >= 3 ? ((raw_op % 10000) ÷ 1000 == 0 ? read_pos(c, 2) : read_val(c, 2)) : 0
	)
end

read_pos(c::Computer, offset) = c.mem[read_val(c, offset)+1]
read_val(c::Computer, offset) = c.mem[c.ptr+offset]

write_pos(c::Computer, offset, value::Int64) = c.mem[read_val(c, offset) + 1] = value

add!(c::Computer, i::Instruction) = write_pos(c, 3, i.param_a + i.param_b)
mul!(c::Computer, i::Instruction) = write_pos(c, 3, i.param_a * i.param_b)
input!(c::Computer, i::Instruction) = write_pos(c, 1, c.input)

function output!(c::Computer, i::Instruction) 
	c.output = i.param_a
end

function jump_if_true!(c::Computer, i::Instruction) 
	if i.param_a != 0
		c.ptr = i.param_b + 1
	end
end

function jump_if_false!(c::Computer, i::Instruction)
	if i.param_a == 0
		c.ptr = i.param_b + 1
	end
end

less_than!(c::Computer, i::Instruction) = write_pos(c, 3, i.param_a < i.param_b ? 1 : 0) 
equals!(c::Computer, i::Instruction) = write_pos(c, 3, i.param_a == i.param_b ? 1 : 0) 

operations = Dict(
	1 => Op(add!, 4),
	2 => Op(mul!, 4),
	3 => Op(input!, 2),
	4 => Op(output!, 2),
	5 => Op(jump_if_true!, 3),
	6 => Op(jump_if_false!, 3),
	7 => Op(less_than!, 4),
	8 => Op(equals!, 4)
)

function run!(computer::Computer)
	while read_val(computer, 0) != 99
		run_step!(computer)
	end
	computer.mem[1]
end

function run_step!(c::Computer)
	i = instruction(c)
	run_instruction!(c, i)
	c
end

function run_instruction!(c::Computer, i::Instruction)
	original_pointer = c.ptr
	i.operation.code(c, i)
	if original_pointer == c.ptr
		c.ptr += i.operation.size
	end
end

end