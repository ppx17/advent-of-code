[CmdletBinding()]
Param(
    [string]$InputFile = 'input-day13.txt',
    [int]$MinimumDelay = 0
);

Function ParseRecord($Record) {
    return [regex]::Match($Record, '([0-9]+): ([0-9]+)');
    #return ($Record -Split(": ")) | ForEach-Object { [int]$_; }
}

$Records = (Get-Content $InputFile | ForEach-Object { ParseRecord($_); });

$Layers = [System.Collections.ArrayList]@();

# Build an array with all the gaps filled. Index is the layer, value is the range.
foreach($Record in $Records) {

    if([int]($Record.Groups[1].Value) -gt $Layers.Count) {
        for($i=$Layers.Count; $i -lt ([int]$Record.Groups[1].Value); $i++) {
            [void]$Layers.Add(0);
        }
    }
    [void]$Layers.Add(([int]$Record.Groups[2].Value));
}

Function CalculateTotalSeverity($Delay) {
    $TotalSeverity = 0;
    $TravelingRow = 1;
    for($PicoSecond=$Delay;$PicoSecond -lt ($Delay + $Layers.Count); $PicoSecond++) {
        $Depth = ($PicoSecond - $Delay);
        $CurrentRange = $Layers[$Depth];
        Write-Verbose "PS: ${PicoSecond} Depth: ${Depth} Range: ${CurrentRange}"
        # Only the scanner on the current position is important
        if($CurrentRange -lt $TravelingRow) {
            # Scanner doesn't have enough range to see us here...
            Write-Verbose "Scanner with 0 range`n";
            continue;
        }
        
        $TotalTravelDistance = ($CurrentRange * 2) - 2;
        if($PicoSecond % $TotalTravelDistance -ne 0) {
            Write-Verbose "Scanner not in top row`n";
            # Not top row
            Continue;
        }
        $Severity = ($Depth * $CurrentRange);
        # Sometimes we get hit at a depth of 0, but we do need some severity to be able to detect the hit
        if($Severity -eq 0) { $Severity++; }
        Write-Verbose "Hit! ${Depth} x ${CurrentRange} = ${Severity}"
        $TotalSeverity += $Severity;
    }
    return $TotalSeverity;
}

#Write-Output (CalculateTotalSeverity($Delay));

$Delay = $MinimumDelay - 1;
do {
    $Delay++;
    $Severity = CalculateTotalSeverity($Delay);
}while($Severity -gt 0);

#29508 too low

Write-Output $Delay;
