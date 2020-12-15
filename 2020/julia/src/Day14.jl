module Day14
include("Aoc.jl")
using .Aoc
using Printf

pattern = r"(?<cmd>\w+)(\[(?<mem>\d+)\])? = (?<val>.*)"
commands = [m for m in eachmatch(pattern, Aoc.input_string(14))]

function part1()
    mask = ""
    mem = Dict()
    for cmd in commands
        if cmd["cmd"] == "mask" 
            mask = cmd["val"]
            continue
        end
        mem[cmd["mem"]] = apply_mask(mask, parse(Int64, cmd["val"]))
    end
    sum(values(mem))
end

function apply_mask(mask::AbstractString, value::Int64)
    m_zeroes = parse(Int64, join(map(x -> x == '0' ? '0' : '1', collect(mask))), base=2)
    m_ones = parse(Int64, replace(mask, 'X' => '0'), base=2)
    value & m_zeroes | m_ones
end

function part2()
    mask = ""
    mem = Dict()
    for cmd in commands
        if cmd["cmd"] == "mask" 
            mask = cmd["val"]
            continue
        end

        for a in addresses_for_mask(parse(Int64, cmd["mem"]), mask)
            mem[a] = parse(Int64, cmd["val"])
        end
    end
    sum(values(mem))
end

function addresses_for_mask(address::Int64, mask::AbstractString)
    addresses = [ 0 ]
    for i=1:length(mask)
        if mask[i] == 'X'
            for a in 1:length(addresses)
                push!(addresses, addresses[a] << 1)
                addresses[a] = addresses[a] << 1 + 1
            end
        elseif mask[i] == '0' 
            for a in 1:length(addresses)
                addresses[a] = (addresses[a] << 1) + (address >> (length(mask) - i) & 1)
            end
        elseif mask[i] == '1'
            for a in 1:length(addresses)
                addresses[a] = addresses[a] << 1 + 1
            end
        end
    end
    addresses
end
end