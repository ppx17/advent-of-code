Param(
    [string]$InputFile = 'input-day13.txt',
    [int]$MinimumDelay = 0
);

# [0] = Distance; [1] = Depth
$Scanners = (Get-Content $InputFile | ForEach-Object { ,([int[]]@( ($_ -split ": "))) });

Function CalculateTotalSeverity($Delay, $AllHitsHaveSeverity) {
    $TotalSeverity = 0;
    foreach($Scanner in $Scanners) {
        $PicoSecond = $Scanner[0] + $Delay;
        $Depth = $Scanner[0];
        $CurrentRange = $Scanner[1];

        $TotalTravelDistance = ($CurrentRange * 2) - 2;
        if($PicoSecond % $TotalTravelDistance -ne 0) {
            # Not top row
            Continue;
        }
        $Severity = ($Depth * $CurrentRange);
        # Sometimes we get hit at a depth of 0, but we do need some severity to be able to detect the hit
        if($Severity -eq 0 -and $AllHitsHaveSeverity -eq $true) { $Severity++; }
        $TotalSeverity += $Severity;
    }
    return $TotalSeverity;
}

Write-Output ("Part 1: {0}" -f (CalculateTotalSeverity(0)));
$Delay = $MinimumDelay;
while((CalculateTotalSeverity -Delay $Delay -AllHitsHaveSeverity $true) -gt 0) {
    $Delay++;
}
Write-Host "Part 2: ${Delay}";
