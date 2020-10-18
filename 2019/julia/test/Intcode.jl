using Test
include("../src/Intcode.jl")
using .Intcode

@testset "Day2 Intcode Computer" begin
	@test Intcode.run!(Intcode.Computer([1,0,0,0,99])) == 2

	let c = Intcode.Computer([1,9,10,3,2,3,11,0,99,30,40,50])
		Intcode.run_step!(c)
		@test c.mem == [1,9,10,70,2,3,11,0,99,30,40,50]
		Intcode.run_step!(c)
		@test c.mem == [3500,9,10,70,2,3,11,0,99,30,40,50]
	end

	let c = Intcode.Computer([1,0,0,0,99])
		Intcode.run!(c)
		@test c.mem == [2,0,0,0,99]
	end

	let c = Intcode.Computer([2,3,0,3,99])
		Intcode.run!(c)
		@test c.mem == [2,3,0,6,99]
	end

	let c = Intcode.Computer([2,4,4,5,99,0])
		Intcode.run!(c)
		@test c.mem == [2,4,4,5,99,9801]
	end

	let c = Intcode.Computer([1,1,1,4,99,5,6,0,99])
		Intcode.run!(c)
		@test c.mem == [30,1,1,4,2,5,6,0,99]
	end
end

@testset "Day5 Intcode Computer" begin
	let c = Intcode.Computer([1101,100,-1,4,0])
		Intcode.run!(c)
		@test c.mem == [1101,100,-1,4,99]
	end

	let c = Intcode.Computer([3,0,99])
		@test c.ptr == 1
		@test c.mem == [3,0,99]
		c.input = [89]
		Intcode.run_step!(c)
		@test c.ptr == 3
		@test c.mem == [89,0,99]
	end

	let c = Intcode.Computer([3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9])
		c.input = [5]
		Intcode.run!(c)
		@test c.output == 1
	end

	let c = Intcode.Computer([3,3,1105,-1,9,1101,0,0,12,4,12,99,1])
		c.input = [5]
		Intcode.run!(c)
		@test c.output == 1
	end

	let
		c_lt = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])
		c_eq = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])
		c_gt = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])

		c_lt.input = [7]
		c_eq.input = [8]
		c_gt.input = [9]

		Intcode.run!(c_lt)
		Intcode.run!(c_eq)
		Intcode.run!(c_gt)

		@test c_lt.output == 999
		@test c_eq.output == 1000
		@test c_gt.output == 1001
	end
end