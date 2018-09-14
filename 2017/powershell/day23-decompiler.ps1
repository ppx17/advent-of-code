[CmdletBinding()]
Param(
    [string]$InputFile = '../input/input-day23.txt'
);

# Helps a little with decompiling, leaves jumps up to you.

$Instructions = Get-Content $InputFile;

Class Helper {
    [String]static Variable([string]$Var) {
        return ("`${0}" -f $Var.ToLower());
    }

    [String]static VarIs([string]$Var) {
        return ("{0} = " -f [Helper]::Variable($Var));
    }

    [String]static ResolveNameOrValue([string]$NameOrValue) {
        if($NameOrValue -match "^[a-z]+$") {
            return ("`${0}" -f $NameOrValue.ToLower());
        }
        return $NameOrValue;
    }

    [string]static Line([int]$Line) {
        return ("  # L{0}" -f $Line);
    }
}

$Line = 1;
Foreach($InstructionText in $Instructions) {

    $Instruction = $InstructionText -Split " ";

    switch($Instruction[0]) {
        "set" { 
            Write-Output ([Helper]::VarIs($Instruction[1]) + 
                [Helper]::ResolveNameOrValue($Instruction[2]) +
                [Helper]::Line($Line));
        }
        "sub" { 
            Write-Output ([Helper]::Variable($Instruction[1]) + 
                " -= " +
                [Helper]::ResolveNameOrValue($Instruction[2]) +
                [Helper]::Line($Line));     
        }
        "mul" { 
            Write-Output ([Helper]::Variable($Instruction[1]) + 
                " *= " +
                [Helper]::ResolveNameOrValue($Instruction[2]) +
                [Helper]::Line($Line));     
        }
    }

    $Line++;
}