use std::fs::File;
use std::io::{self, BufReader};
use std::io::prelude::*;

fn main() -> io::Result<()> {
    let f = File::open("../../input/input-day1.txt")?;
    let f = BufReader::new(f);

    let mut sum: i64 = 0;
    let mut compensated_sum: i64 = 0;

    for line in f.lines() {
        let line = line.unwrap();
        if line.is_empty() {
            continue;
        }
        let mass = line.parse::<i64>().unwrap();
        sum += fuel_for_mass(mass);
        compensated_sum += compensated_fuel_for_mass(mass);
    }

    println!("Part 1: {}", sum);
    println!("Part 2: {}", compensated_sum);

    Ok(())
}

fn fuel_for_mass(mass: i64) -> i64 {
    return (mass / 3) - 2;
}

fn compensated_fuel_for_mass(mass: i64) -> i64 {
    let mut sum: i64 = 0;
    let mut last_fuel = fuel_for_mass(mass);
    while last_fuel > 0 {
        sum += last_fuel;
        last_fuel = fuel_for_mass(last_fuel);
    }
    return sum;
}
