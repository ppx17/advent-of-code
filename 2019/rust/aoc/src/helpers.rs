pub mod input {
    use std::fs::File;
    use std::io;
    use std::io::Read;

    pub fn read_input(day: i8) -> Result<String, io::Error> {
        let f = File::open(format!("../../input/input-day{}.txt", day));
        let mut f = match f {
            Ok(file) => file,
            Err(_error) => {
                File::open(format!("../input/input-day{}.txt", day)).unwrap()
            }
        };
        let mut contents = String::new();

        f.read_to_string(&mut contents)?;

        header(day);

        Ok(contents.to_string())
    }

    fn header(day: i8) {
        println!();
        println!("Day {}", day);
        println!();
    }
}

pub mod perf {
    use std::time::Duration;
    use std::time::Instant;

    pub struct InitialPerformance {
        setup: Instant,
    }

    impl InitialPerformance {
        pub fn new() -> InitialPerformance {
            InitialPerformance {
                setup: Instant::now()
            }
        }
        pub fn part1(self) -> Part1Performance {
            Part1Performance { setup: self.setup.elapsed(), part1: Instant::now() }
        }
    }

    pub struct Part1Performance {
        setup: Duration,
        part1: Instant,
    }

    impl Part1Performance {
        pub fn part2(self) -> Part2Performance {
            Part2Performance { setup: self.setup, part1: self.part1.elapsed(), part2: Instant::now() }
        }
    }

    pub struct Part2Performance {
        setup: Duration,
        part1: Duration,
        part2: Instant,
    }

    impl Part2Performance {
        pub fn print(&self) {
            println!();
            let part2 = self.part2.elapsed();
            println!(
                "Setup:  {}\tPart1: {}\tPart2: {}\tTotal: {}",
                self.duration(self.setup.as_micros()),
                self.duration(self.part1.as_micros()),
                self.duration(part2.as_micros()),
                self.duration(self.setup.as_micros()
                    + self.part1.as_micros()
                    + part2.as_micros())
            );
        }

        fn duration(&self, micros: u128) -> String {
            if micros < 1000 {
                return format!("{} µs", micros);
            }
            return format!("{} ms", (micros as f64) / 1000.0);
        }
    }
}