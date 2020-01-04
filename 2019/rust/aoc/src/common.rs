
pub struct IntCode {
    pub memory: Vec<usize>,
    initial_memory: Vec<usize>,
    pointer: usize,
    halted: bool,
}

impl IntCode {
    pub fn new_with_memory_size(instructions: &String, memory_size: usize) -> IntCode {
        let mut memory: Vec<usize> = Self::parse(instructions);
        memory.resize(memory_size, 0);
        Self::make(
            &memory
        )
    }

    pub fn new(instructions: &String) -> IntCode {
        let memory: Vec<usize> = Self::parse(instructions);
        Self::make(
            &memory
        )
    }

    pub fn make(memory: &Vec<usize>) -> IntCode {
        IntCode {
            memory: memory.clone(),
            initial_memory: memory.clone(),
            pointer: 0,
            halted: false,
        }
    }

    fn parse(instructions: &String) -> Vec<usize> {
        instructions
                .split(',')
                .map(|s| s.trim().parse().unwrap())
                .collect()
    }

    pub fn run(&mut self) {
        while !self.halted {
            self.tick();
        }
    }

    pub fn tick(&mut self) {
        match self.instruction() {
            1 => {
                self.write_c(self.a() + self.b());
                self.pointer += 4
            }
            2 => {
                self.write_c(self.a() * self.b());
                self.pointer += 4
            }
            99 => { self.halted = true }
            i => panic!("Invalid instruction {}", i),
        }
    }

    pub fn reset(&mut self) {
        self.memory = self.initial_memory.clone();
        self.pointer = 0;
        self.halted = false;
    }

    pub fn read(&self, address: usize) -> usize {
        self.memory[address]
    }

    pub fn write(&mut self, address: usize, value: usize) {
        self.memory[address] = value;
    }

    fn instruction(&self) -> usize {
        self.read(self.pointer)
    }

    fn a(&self) -> usize {
        self.read(self.read(self.pointer + 1))
    }

    fn b(&self) -> usize {
        self.read(self.read(self.pointer + 2))
    }

    fn write_c(&mut self, value: usize) {
        self.write(self.read(self.pointer + 3), value);
    }
}