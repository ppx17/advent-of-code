use std::io;

fn main() -> io::Result<()> {
    let contents = aoc::input::read_input(2)?;
    let perf = aoc::perf::InitialPerformance::new();

    let memory: Vec<usize> = contents
        .split(',')
        .map(|s| s.trim().parse().unwrap())
        .collect();


    let perf = perf.part1();
    println!("Part 1: {}", int_code(&memory, 12, 2));
    let perf = perf.part2();
    println!("Part 2: {}", part_two(&memory));

    perf.print();

    Ok(())
}

fn int_code(memory: &Vec<usize>, verb: usize, noun: usize) -> usize {
    let mut pointer = 0_usize;
    let mut work_memory = memory.clone();

    work_memory[1] = verb;
    work_memory[2] = noun;

    for _ in 0..500 {
        let instruction = work_memory[pointer];

        if instruction == 99 {
            return work_memory[0];
        }

        let a = work_memory[work_memory[pointer + 1]];
        let b = work_memory[work_memory[pointer + 2]];
        let result_pointer = work_memory[pointer + 3];

        if instruction == 1 {
            work_memory[result_pointer] = a + b;
        }

        if instruction == 2 {
            work_memory[result_pointer] = a * b;
        }

        pointer += 4;
    }

    return 0;
}

fn part_two(memory: &Vec<usize>) -> usize {
    for verb in (0..99).rev() {
        for noun in (0..99).rev() {
            if int_code(&memory, verb, noun) == 19690720 {
                return 100 * verb + noun;
            }
        }
    }
    return 0;
}
