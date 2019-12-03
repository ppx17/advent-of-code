use std::io;

fn main() -> io::Result<()> {
    let contents = aoc::input::read_input(1)?;
    let perf = aoc::perf::InitialPerformance::new();

    let masses: Vec<i32> = contents
        .trim()
        .split_whitespace()
        .map(|s| s.parse().unwrap())
        .collect();

    let perf = perf.part1();
    let part1: i32 = masses.iter().map(|x| fuel_for_mass(&x)).sum();
    let perf = perf.part2();
    let part2: i32 = masses.iter().map(|x| compensated_fuel_for_mass(*x)).sum();

    println!("Part 1: {}", part1);
    println!("Part 2: {}", part2);
    
    perf.print();

    Ok(())
}

fn fuel_for_mass(mass: &i32) -> i32 {
    return (mass / 3) - 2;
}

fn compensated_fuel_for_mass(mass: i32) -> i32 {
    let mut sum: i32 = 0;
    let mut last_fuel = fuel_for_mass(&mass);
    while last_fuel > 0 {
        sum += last_fuel;
        last_fuel = fuel_for_mass(&last_fuel);
    }
    sum
}
