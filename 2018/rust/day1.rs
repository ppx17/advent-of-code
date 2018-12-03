use std::fs;

fn main() {
    let filename = "../input/input-day1.txt";
    let contents = fs::read_to_string(filename)
        .unwrap()
        .lines()
        .map(|s| s.parse::<i32>().unwrap());

    println!("Content: \n{}", contents)
}