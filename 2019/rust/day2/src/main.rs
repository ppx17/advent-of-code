use std::io;
use aoc::helpers::{input, perf};
use aoc::common::IntCode;

fn main() -> io::Result<()> {
    let contents = input::read_input(2)?;
    let perf = perf::InitialPerformance::new();

    let mut computer = IntCode::new_with_memory_size(&contents, 1024);


    let perf = perf.part1();
    println!("Part 1: {}", run_computer(&mut computer, 12, 2));
    let perf = perf.part2();
    println!("Part 2: {}", part_two(&mut computer));

    perf.print();

    Ok(())
}

fn run_computer(computer: &mut IntCode, verb: usize, noun: usize) -> usize {
    computer.reset();

    computer.write(1, verb);
    computer.write(2, noun);

    computer.run();

    computer.read(0)
}

fn part_two(computer: &mut IntCode) -> usize {
    for verb in (0..99).rev() {
        for noun in (0..99).rev() {
            if run_computer(computer, verb, noun) == 19690720 {
                return 100 * verb + noun;
            }
        }
    }
    0
}
