module Day4
include("Aoc.jl")
using .Aoc

required_fields = ["byr", "iyr", "eyr", "hgt", "hcl", "ecl", "pid"]
eye_colors = ["amb", "blu", "brn", "gry", "grn", "hzl", "oth"]

data = Aoc.input_string(4)

passports = map(split(data, r"\n\n")) do pd
    passport = Dict{String, String}()
    for field in split(strip(pd), r"[\n\s]")
        (key, val) = split(String(field), ":")
        passport[key] = val
    end
    passport
end

valid_passports = filter(passport -> findfirst(req -> !haskey(passport, req), required_fields) == nothing, passports)

year(val, min, max) = min <= parse(Int64, val) <= max

function height(input)
    m = match(r"^(?<len>[0-9]{2,3})(?<unit>in|cm)$", input)
    m != nothing && (
        (m[:unit] == "cm" && 150 <= parse(Int32, m[:len]) <= 193) 
        || (m[:unit] == "in" && 59 <= parse(Int32, m[:len]) <= 76)
    )
end

part1() = size(valid_passports)[1]
part2() = valid_passports|>
    list -> filter(passport -> year(passport["byr"], 1920, 2002), list)|>
    list -> filter(passport -> year(passport["iyr"], 2010, 2020), list)|>
    list -> filter(passport -> year(passport["eyr"], 2020, 2030), list)|>
    list -> filter(passport -> height(passport["hgt"]), list)|>
    list -> filter(passport -> occursin(r"^\#[0-9a-f]{6}$", passport["hcl"]), list)|>
    list -> filter(passport -> passport["ecl"] ∈ eye_colors, list)|>
    list -> filter(passport -> occursin(r"^[0-9]{9}$", passport["pid"]), list)|>
    size|>
    first
end