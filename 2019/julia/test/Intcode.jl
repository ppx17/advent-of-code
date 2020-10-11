using Test
include("../src/Intcode.jl")
using .Intcode

@testset "Day2 Intcode Computer" begin
	@test Intcode.run!(Intcode.Computer([1,0,0,0,99])) == 2

	example = Intcode.Computer([1,9,10,3,2,3,11,0,99,30,40,50])
	Intcode.run_step!(example)
	@test example.mem == [1,9,10,70,2,3,11,0,99,30,40,50]
	Intcode.run_step!(example)
	@test example.mem == [3500,9,10,70,2,3,11,0,99,30,40,50]

	test1 = Intcode.Computer([1,0,0,0,99])
	Intcode.run!(test1)
	@test test1.mem == [2,0,0,0,99]

	test2 = Intcode.Computer([2,3,0,3,99])
	Intcode.run!(test2)
	@test test2.mem == [2,3,0,6,99]

	test5 = Intcode.Computer([2,4,4,5,99,0])
	Intcode.run!(test5)
	@test test5.mem == [2,4,4,5,99,9801]

	test6 = Intcode.Computer([1,1,1,4,99,5,6,0,99])
	Intcode.run!(test6)
	@test test6.mem == [30,1,1,4,2,5,6,0,99]
end

@testset "Day5 Intcode Computer" begin
	test1 = Intcode.Computer([1101,100,-1,4,0])
	Intcode.run!(test1)
	@test test1.mem == [1101,100,-1,4,99]

	test2 = Intcode.Computer([3,0,99])
	@test test2.ptr == 1
	@test test2.mem == [3,0,99]
	test2.input = 89
	Intcode.run_step!(test2)
	@test test2.ptr == 3
	@test test2.mem == [89,0,99]

	test3 = Intcode.Computer([3,12,6,12,15,1,13,14,13,4,13,99,-1,0,1,9])
	test3.input = 5
	Intcode.run!(test3)
	@test test3.output == 1

	test4 = Intcode.Computer([3,3,1105,-1,9,1101,0,0,12,4,12,99,1])
	test4.input = 5
	Intcode.run!(test4)
	@test test4.output == 1

	test5 = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])
	test6 = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])
	test7 = Intcode.Computer([3,21,1008,21,8,20,1005,20,22,107,8,21,20,1006,20,31,1106,0,36,98,0,0,1002,21,125,20,4,20,1105,1,46,104,999,1105,1,46,1101,1000,1,20,4,20,1105,1,46,98,99])

	test5.input = 7
	test6.input = 8
	test7.input = 9

	Intcode.run!(test5)
	Intcode.run!(test6)
	Intcode.run!(test7)

	@test test5.output == 999
	@test test6.output == 1000
	@test test7.output == 1001
end