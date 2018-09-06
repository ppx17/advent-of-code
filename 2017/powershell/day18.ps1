[CmdletBinding()]
Param(
    [string]$InputFile = '../input/input-day18.txt'
);

Class AssemblyRegistry {
    [System.Collections.Hashtable]$Registry = [System.Collections.Hashtable]@{}

    [Int64]Get([string]$Name) {
        if($null -eq $this.Registry[$Name]) {
            return 0;
        }
        return $this.Registry[$Name];
    }

    [void]Set([string]$Name, [string]$NameOrValue) {
        $this.Registry[$Name] = $this.ResolveNameOrValue($NameOrValue);
    }

    [void]Add([string]$Name, [string]$NameOrValue) {
        $this.Set($Name, ($this.Get($Name) + $this.ResolveNameOrValue($NameOrValue)));
    }

    [void]Mul([string]$Name1,  [string]$NameOrValue) {
        $this.Set([string]$Name1, ($this.Get($Name1) * $this.ResolveNameOrValue($NameOrValue)));
    }

    [void]Mod([string]$Name, [string]$NameOrValue) {
        $this.Set([string]$Name, ($this.Get($Name) % $this.ResolveNameOrValue($NameOrValue)));
    }

    [Int64]ResolveNameOrValue([string]$NameOrValue) {
        if($NameOrValue -match "^[a-z]+$") {
            return $this.Get($NameOrValue);
        }
        return $NameOrValue;
    }
}

Class AssemblyInterpreter {

    [int]$LastFrequency;
    [AssemblyRegistry]$Registry;
    [array]$Instructions;
    [int]$CurrentInstruction;

    AssemblyInterpreter([array]$Instructions) {
        $this.Instructions = $Instructions;
        $this.CurrentInstruction = 0;
        $this.Registry = [AssemblyRegistry]::new();
    }

    [void]Run() {
        $this.RunInstruction(0);
    }

    [bool]IsValidInstruction($InX) {
        return $InX -ge 0 -and $InX -lt $this.Instructions.Count;
    }

    [void]RunInstruction($InX) {
        if( -not $this.IsValidInstruction($InX)) {
            # End of program reached, terminate
            return;
        }

        $Instruction = $this.GetInstruction($InX);
        $Jump = 1;
        switch($Instruction[0]) {
            "snd" { $this.LastFrequency = $this.Registry.Get($Instruction[1]); }
            "set" { $this.Registry.Set($Instruction[1], $Instruction[2]); }
            "add" { $this.Registry.Add($Instruction[1], $Instruction[2]); }
            "mul" { $this.Registry.Mul($Instruction[1], $Instruction[2]); }
            "mod" { $this.Registry.Mod($Instruction[1], $Instruction[2]); }
            "rcv" { if($this.Registry.Get($Instruction[1]) -gt 0) { return; } }
            "jgz" { 
                if( $this.Registry.Get($Instruction[1]) -gt 0) { 
                    $Jump = [int]$Instruction[2];
                }
            }
        }
        $this.RunInstruction($InX + $Jump);
    }

    [array]GetInstruction($InX) {
        return ($this.Instructions[$InX] -Split " ");
    }


}


$Instructions = Get-Content $InputFile;
$Interpreter = [AssemblyInterpreter]::new($Instructions);
$Interpreter.Run();

Write-Output $Interpreter.LastFrequency;
