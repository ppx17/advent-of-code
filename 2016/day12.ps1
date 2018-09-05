Param(
    [string]$InputFile = 'input-day12.txt'
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

Class AssemblyInterpreter 
{
    [AssemblyRegistry]$Registry;
    [array]$Instructions;
    [string[][]]$ParsedInstructions;
    [int]$LastD = 0;

    AssemblyInterpreter([array]$Instructions) {
        $this.Instructions = $Instructions;
        $this.Registry = [AssemblyRegistry]::new();
        $this.ParseInstructions();
    }

    [void]ParseInstructions() {
        $this.ParsedInstructions = New-Object string[][] $this.Instructions.Count,3;
        for($InX = 0; $InX -lt $this.Instructions.Count; $InX++) {
            $this.ParsedInstructions[$InX] = ($this.Instructions[$InX] -Split " ");
        }
    }

    [void]Run() {
        $InX = 0;
        do {
            $InX = $this.RunInstruction($InX);
            if($this.Registry.Registry["d"] -ne $this.LastD) {
                Write-Host ($This.Registry.Registry | ConvertTo-Json -Compress);
                $this.LastD = $this.Registry.Get("d");
            }
        }while($this.IsValidInstruction($InX));
    }

    [bool]IsValidInstruction($InX) {
        return $InX -ge 0 -and $InX -lt $this.Instructions.Count;
    }

    [int]RunInstruction($InX) {

        $Instruction = $this.GetInstruction($InX);
        $Jump = 1;
        switch($Instruction[0]) {
            "cpy" { $this.Registry.Set($Instruction[2], $Instruction[1]); }
            "inc" { $this.Registry.Add($Instruction[1], 1); }
            "dec" { $this.Registry.Add($Instruction[1], -1); }
            "jnz" { 
                if( $this.Registry.Get($Instruction[1]) -gt 0) { 
                    $Jump = [int]$Instruction[2];
                }
            }
        }
        return $InX + $Jump;
    }

    [array]GetInstruction($InX) {
        return $this.ParsedInstructions[$InX];
    }
}

$Instructions = Get-Content $InputFile;
$Interpreter = [AssemblyInterpreter]::new($Instructions);
$Interpreter.Run();

Write-Host ($Interpreter.Registry.Registry | ConvertTo-Json -Compress);
# 9227465 too high