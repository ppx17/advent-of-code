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
    [int]$NextInstruction;
    [bool]$IsRunning;
    [bool]$IsWaitingForMessage;
    [int]$ProcessId;
    [System.Collections.Queue]$ReceiveQueue;

    AssemblyInterpreter([array]$Instructions, [int]$ProcessId) {
        $this.Instructions = $Instructions;
        $this.Registry = [AssemblyRegistry]::new();
        $this.Registry.Set("p", $ProcessId);
        $this.ProcessId = $ProcessId;
        $this.IsRunning = $true;
        $this.IsWaitingForMessage = $false;
        $this.ReceiveQueue = [System.Collections.Queue]::new();
    }

    [bool]IsValidInstruction($InX) {
        return $InX -ge 0 -and $InX -lt $this.Instructions.Count;
    }

    [object]RunInstruction($InX) {
        if( -not $this.IsValidInstruction($InX)) {
            # End of program reached, terminate
            $this.Terminate();
            return $null;
        }
        $Send = $null;
        $Instruction = $this.GetInstruction($InX);
        $Jump = 1;
        switch($Instruction[0]) {
            "snd" { $Send = $this.Registry.Get($Instruction[1]); }
            "set" { $this.Registry.Set($Instruction[1], $Instruction[2]); }
            "add" { $this.Registry.Add($Instruction[1], $Instruction[2]); }
            "mul" { $this.Registry.Mul($Instruction[1], $Instruction[2]); }
            "mod" { $this.Registry.Mod($Instruction[1], $Instruction[2]); }
            "rcv" { 
                if($this.ReceiveQueue.Count -eq 0) {
                    # Deadlock
                    $this.IsWaitingForMessage = $true;
                    return $null;
                }
                $this.Registry.Set($Instruction[1], $this.ReceiveQueue.Dequeue());
             }
            "jgz" { 
                $X = $this.Registry.ResolveNameOrValue($Instruction[1]);
                $Y = $this.Registry.ResolveNameOrValue($Instruction[2]);
                if( $X -gt 0) { 
                    $Jump = $Y;
                }
            }
        }
        $this.NextInstruction = $InX + $Jump;

        return $Send;
    }

    [object]RunNextInstruction() {
        if($this.IsRunning -ne $true) {
            Write-Error "Running instruction for terminated program.";
            return $null;
        }
        return $this.RunInstruction($this.NextInstruction);
    }

    [void]ReceiveMessage([Int64]$Message) {
        $this.IsWaitingForMessage = $false;
        $this.ReceiveQueue.Enqueue($Message);
    }

    [array]GetInstruction($InX) {
        return ($this.Instructions[$InX] -Split " ");
    }

    [void]Terminate() {
        $this.IsRunning = $false;
    }
}

$Instructions = Get-Content $InputFile;
$Instance0 = [AssemblyInterpreter]::new($Instructions, 0);
$Instance1 = [AssemblyInterpreter]::new($Instructions, 1);

$MessagesSentInstance1 = 0;
while($Instance1.IsRunning) {
    # We only continue while instance 1 is running, since our goal is to count the snd messages executed by instance 1.

    if($Instance0.IsRunning -and -not $Instance0.IsWaitingForMessage) {
        $Message = $Instance0.RunNextInstruction();
        if($null -ne $Message) {
            $Instance1.ReceiveMessage($Message);
        }
    }
    if($Instance1.IsRunning -and -not $Instance1.IsWaitingForMessage) {
        $Message = $Instance1.RunNextInstruction();
        if($null -ne $Message) {
            $MessagesSentInstance1++;
            $Instance0.ReceiveMessage($Message);
        }
    }
    if($Instance0.IsWaitingForMessage -and $Instance1.IsWaitingForMessage) {
        $Instance0.Terminate();
        $Instance1.Terminate();
    }
}


Write-Output $MessagesSentInstance1;
