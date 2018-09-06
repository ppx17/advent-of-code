[CmdletBinding()]
Param(
    [string]$InputFile = '../input/input-day23.txt',
    [switch]$Part2
);

Class AssemblyRegistry {
    [System.Collections.Hashtable]$Registry = [System.Collections.Hashtable]@{}

    [Int64]Get([string]$Name) {
        if($null -eq $this.Registry[$Name]) {
            return 0;
        }
        return $this.Registry[$Name];
    }

    [Int64]ResolveNameOrValue([string]$NameOrValue) {
        if($NameOrValue -match "^[a-z]+$") {
            return $this.Get($NameOrValue);
        }
        return $NameOrValue;
    }

    [void]Set([string]$Name, [string]$NameOrValue) {
        $this.Registry[$Name] = $this.ResolveNameOrValue($NameOrValue);
    }

    [void]Add([string]$Name, [string]$NameOrValue) {
        $this.Set($Name, ($this.Get($Name) + $this.ResolveNameOrValue($NameOrValue)));
    }

    [void]Sub([string]$Name, [string]$NameOrValue) {
        $this.Set($Name, ($this.Get($Name) - $this.ResolveNameOrValue($NameOrValue)));
    }

    [void]Mul([string]$Name,  [string]$NameOrValue) {
        $this.Set($Name, ($this.Get($Name) * $this.ResolveNameOrValue($NameOrValue)));
    }

    [void]Mod([string]$Name, [string]$NameOrValue) {
        $this.Set($Name, ($this.Get($Name) % $this.ResolveNameOrValue($NameOrValue)));
    }
}

Class AssemblyInterpreter {

    [AssemblyRegistry]$Registry;
    [array]$Instructions;
    [int]$CurrentInstruction;
    [System.Collections.Hashtable]$InstructionCounter;

    AssemblyInterpreter([array]$Instructions) {
        $this.Instructions = $Instructions;
        $this.CurrentInstruction = 0;
        $this.Registry = [AssemblyRegistry]::new();
        $this.InstructionCounter = [System.Collections.Hashtable]@{};
    }

    [void]Run() {
        $InX = 0;
        while($this.IsValidInstruction($InX)) {
            $InX = $this.RunInstruction($InX);
        }
    }

    [bool]IsValidInstruction($InX) {
        return $InX -ge 0 -and $InX -lt $this.Instructions.Count;
    }

    [Int64]RunInstruction($InX) {
        $Instruction = $this.GetInstruction($InX);
        $Jump = 1;
        switch($Instruction[0]) {
            "set" { $this.Registry.Set($Instruction[1], $Instruction[2]); }
            "add" { $this.Registry.Add($Instruction[1], $Instruction[2]); }
            "sub" { $this.Registry.Sub($Instruction[1], $Instruction[2]); }
            "mul" { $this.Registry.Mul($Instruction[1], $Instruction[2]); }
            "mod" { $this.Registry.Mod($Instruction[1], $Instruction[2]); }
            "jnz" {
                $CheckValue = $this.Registry.ResolveNameOrValue($Instruction[1]);
                $Distance =  $this.Registry.ResolveNameOrValue($Instruction[2]);
                if($CheckValue -ne 0) {
                    $Jump = $Distance
                }
            }
        }

        $this.CountInstruction($Instruction[0]);

        return ($InX + $Jump);
    }

    [void]CountInstruction($Instruction) {
        if($null -eq $this.InstructionCounter[$Instruction]) {
            $this.InstructionCounter[$Instruction] = 0;
        }
        $this.InstructionCounter[$Instruction]++;
    }

    [array]GetInstruction($InX) {
        return $this.Instructions[$InX];
    }
}

$InstructionTexts = Get-Content $InputFile;
$Instructions = [System.Collections.ArrayList]@();
$InstructionTexts | Foreach-Object { [void]$Instructions.Add($_.Split(" ")); }
$Interpreter = [AssemblyInterpreter]::new($Instructions);
if($Part2) {
    $Interpreter.Registry.Set("a", 1);
}
$Interpreter.Run();

Write-Output "Instruction counters:";
$Interpreter.InstructionCounter | Format-Table;

Write-Output "Registry content:";
$Interpreter.Registry.Registry | Format-Table;