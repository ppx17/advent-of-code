### A Pluto.jl notebook ###
# v0.11.14

using Markdown
using InteractiveUtils
include("Aoc.jl")

# ╔═╡ ac364e24-0164-11eb-2361-fb8b883b7c68
input = filter(x -> x != "", Aoc.input_lines(1))

# ╔═╡ bec36f16-0164-11eb-0170-d55d9fde8548
md"""
# Part 1
"""

# ╔═╡ b5214fb0-015e-11eb-354d-d1df36a937f8
part1(mass) = (mass ÷ 3) - 2

# ╔═╡ 6ca9db20-015f-11eb-0684-27958c76db96
@assert part1(12) == 2

# ╔═╡ fdc4a004-0164-11eb-1ba7-27db9795463c
@assert part1(14) == 2

# ╔═╡ ed8e0f64-0172-11eb-174c-573b1596dddd
solve_part(method) = sum(map(x -> method(Base.parse(Int64, x)), input))

# ╔═╡ 85aae7e4-0160-11eb-277d-95f1dc8e8473
begin
        ans1 = solve_part(part1)
        println("Part 1: ", ans1)
end

# ╔═╡ aafe1ef2-0172-11eb-3e47-495d3c19f58b
@assert ans1 == 3361976

# ╔═╡ 132aec12-0167-11eb-2be3-f5f8b7ccfa8a
md"""
# Part 2
"""

# ╔═╡ 18b1e0be-0167-11eb-3b13-299854b4d40a
function part2(mass)
        need = part1(mass)
        total = 0
        while need > 0
                total += need
                need = part1(need)
        end
    total
end


# ╔═╡ 9bd44696-0171-11eb-220b-15624d7678d4
@assert part2(100756) == 50346

# ╔═╡ 477ca744-0171-11eb-3c7f-795223761787
begin
        ans2 = solve_part(part2)
        println("Part 1: ", ans2)
end

# ╔═╡ 4e9fe040-0171-11eb-2ea8-f979e5f33cac
@assert ans2 == 5040085

# ╔═╡ Cell order:
# ╠═ac364e24-0164-11eb-2361-fb8b883b7c68
# ╟─bec36f16-0164-11eb-0170-d55d9fde8548
# ╠═b5214fb0-015e-11eb-354d-d1df36a937f8
# ╠═6ca9db20-015f-11eb-0684-27958c76db96
# ╠═fdc4a004-0164-11eb-1ba7-27db9795463c
# ╠═ed8e0f64-0172-11eb-174c-573b1596dddd
# ╠═85aae7e4-0160-11eb-277d-95f1dc8e8473
# ╠═aafe1ef2-0172-11eb-3e47-495d3c19f58b
# ╟─132aec12-0167-11eb-2be3-f5f8b7ccfa8a
# ╠═18b1e0be-0167-11eb-3b13-299854b4d40a
# ╠═9bd44696-0171-11eb-220b-15624d7678d4
# ╠═477ca744-0171-11eb-3c7f-795223761787
# ╠═4e9fe040-0171-11eb-2ea8-f979e5f33cac
