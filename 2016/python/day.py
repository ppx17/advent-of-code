class Day:
    
    input_lines = []
    
    def __init__(self):
        pass
    
    def from_file(self, input_file):
        with open(input_file, 'r') as content_file:
            self.input_lines = content_file.readlines()
        return self
            
    def part1(self):
        pass
        
    def part2(self):
        pass