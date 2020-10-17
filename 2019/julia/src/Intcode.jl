module Intcode
export run!, Computer
include("Aoc.jl")
using .Aoc

struct Op
	code
	size::Int64
end

struct Instruction
	operation::Op
	param_a::Int64
	param_b::Int64
end

mutable struct Computer
	mem::Vector{Int64}
	ptr::Int64
	input::Vector{Int64}
	output::Int64
	output_callable::Function
	Computer(mem) = new(mem, 1, [])
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
write(c::Computer, offset, value::Int64) = c.mem[read_val(c, offset) + 1] = value

function op_output!(c::Computer, i::Instruction)
	c.output = i.param_a;

	if isdefined(c, :output_callable)
	 	c.ptr += i.operation.size
		c.output_callable(c.output)
	end
end

operations = Dict(
	1 => Op((c, i) -> write(c, 3, i.param_a + i.param_b), 4),
	2 => Op((c, i) -> write(c, 3, i.param_a * i.param_b), 4),
	3 => Op((c, i) -> write(c, 1, popfirst!(c.input)), 2),
	4 => Op(op_output!, 2),
	5 => Op((c, i) -> i.param_a != 0 && (c.ptr = i.param_b + 1), 3),
	6 => Op((c, i) -> i.param_a == 0 && (c.ptr = i.param_b + 1), 3),
	7 => Op((c, i) -> write(c, 3, i.param_a < i.param_b ? 1 : 0) , 4),
	8 => Op((c, i) -> write(c, 3, i.param_a == i.param_b ? 1 : 0) , 4)
)

function run!(computer::Computer)
	while read_val(computer, 0) != 99
		run_step!(computer)
	end
	computer.mem[1]
end

run_step!(c::Computer) = run_instruction!(c, instruction(c))

function run_instruction!(c::Computer, i::Instruction)
	original_pointer = c.ptr
	i.operation.code(c, i)
	if original_pointer == c.ptr
		c.ptr += i.operation.size
	end
	c
end

end