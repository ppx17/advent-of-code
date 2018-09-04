Param(
    [string]$InputFile = 'input-day10.txt'
);

Class Bot
 {
    [int]$Id;
    [int[]]$Values;
    [int]$HighId = -1;
    [int]$LowId = -1;

    [string]$HighTarget;
    [string]$LowTarget;

    Bot([int]$Id, [string]$LowTarget, [int]$LowId, [string]$HighTarget, [int]$HighId) {
        $this.Id = $Id;
        $this.LowTarget = $LowTarget;
        $this.LowId = $LowId;
        $this.HighTarget = $HighTarget;
        $this.HighId = $HighId;
    }

    [void]Receive([int]$Value) {
        $this.Values += $Value;
    }

    [bool]ShouldProcess() {
        return $this.Values.Count -eq 2;
    }

    [void]Reset() {
        $this.HighTarget = "";
        $this.LowTarget = "";
        $this.HighId = -1;
        $this.LowId = -1;
        $this.Values = @();
    }

    [int]LowValue() {
        return $this.Values | Sort-Object | Select-Object -First 1;
    }

    [int]HighValue() {
        return $this.Values | Sort-Object -Descending | Select-Object -First 1;
    }
}

Class BotController
{
    static [BotController] $Instance;

    [System.Collections.Hashtable]$Bots;
    [System.Collections.Hashtable]$OutputBins;

    [System.Collections.Queue]$ProcessQueue;

    [scriptblock]$ProcessCallback;

    BotController() {
        $this.Bots = [System.Collections.Hashtable]::new();
        $this.OutputBins = [System.Collections.Hashtable]::new();
        $this.ProcessQueue = [System.Collections.Queue]::new();
    }

    [void]SetProcessCallback([ScriptBlock]$ScriptBlock) {
        $this.ProcessCallback = $ScriptBlock;
    }

    [void] NewBot([int]$BotId, [string]$LowTarget, [int]$LowId, [string]$HighTarget, [int]$HighId) {
        $this.Bots.Add($BotId, [Bot]::new($BotId, $LowTarget, $LowId, $HighTarget, $HighId));
    }

    [void] SendValueToBot([int]$BotId, [int]$Value) {
        $this.ProcessQueue.Enqueue(@($BotId, $Value));
    }

    [void] Output([int]$OutputBin, [int]$Value) {
        $this.OutputBins[$OutputBin] = $Value;
    }

    [void]RunQueue() {
        while($this.ProcessQueue.Count -gt 0) {
            $this.RunQueueItem();
        }
    }

    [void]RunQueueItem() {
        $QueuedItem = $this.ProcessQueue.Dequeue();
        $BotId = $QueuedItem[0];
        $Value = $QueuedItem[1];

        $this.Bots[$BotId].Receive($Value);
        if($this.Bots[$BotId].ShouldProcess()) {
            if($null -ne $this.ProcessCallback) {
                $this.ProcessCallback.Invoke($this.Bots[$BotId]);
            }
            if($this.Bots[$BotId].HighTarget -eq "bot") {
                $this.SendValueToBot($this.Bots[$BotId].HighId, $this.Bots[$BotId].HighValue());
            }else{
                $this.Output($this.Bots[$BotId].HighId, $this.Bots[$BotId].HighValue());
            }
            if($this.Bots[$BotId].LowTarget -eq "bot") {
                $this.SendValueToBot($this.Bots[$BotId].LowId, $this.Bots[$BotId].LowValue());
            }else{
                $this.Output($this.Bots[$BotId].LowId, $this.Bots[$BotId].LowValue());
            }

            $this.Bots[$BotId].Reset();
        }
    }
}

$Values = [System.Collections.Hashtable]::new();

$BC = [BotController]::new();

# Load instructions
foreach($Instruction in (Get-Content $InputFile)) {
    if($Instruction -Match "^bot ([0-9]+) gives low to (bot|output) ([0-9]+) and high to (bot|output) ([0-9]+)$") {
       $BC.NewBot([int]$Matches[1], $Matches[2],[int]$Matches[3],$Matches[4],[int]$Matches[5]);
    }
    if($Instruction -Match "^value ([0-9]+) goes to bot ([0-9]+)$") {
        $Values.Add(([int]$Matches[1]), ([int]$Matches[2]));
    }
}

$Values.keys | Foreach-Object {
    $Value = $_;
    $BotId = $Values[$_];
   $BC.SendValueToBot($BotId, $Value);
}

$BC.SetProcessCallback({
    param([Bot]$Bot);
    
    if($Bot.HighValue() -eq 61 -and $Bot.LowValue() -eq 17) {
        Write-Host "Part 1: ${BotId}";
    }
});

$BC.RunQueue();

Write-Output ("Part 2: {0}" -f ($BC.OutputBins[0] *$BC.OutputBins[1] *$BC.OutputBins[2]));