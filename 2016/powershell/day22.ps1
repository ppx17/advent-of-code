Param(
    [string]$InputFile = '../input/input-day22.txt'
);
$DfRegex = "\/dev\/grid\/node\-x(?<x>[0-9]+)\-y(?<y>[0-9]+)\s+" +
"(?<size>[0-9]+)T\s+" +
"(?<used>[0-9]+)T\s+" +
"(?<avail>[0-9]+)T\s+" +
"(?<pct>[0-9]+)\%";

$RegexMatches = ([Regex]::Matches((Get-Content $InputFile -Raw), $DfRegex));
$Nodes = $RegexMatches | Foreach-Object { [PSCustomObject]($_.Groups | Foreach-Object { $Output = @{} } { if ($_.Name -ne "0") { $Output[$_.Name] = [int]$_.Value } } { return $Output }); }

$PairCount = 0;
foreach ($A in $Nodes) {
    foreach ($B in $Nodes) {
        if ($A.used -eq 0 -or
            ($A.x -eq $B.x -and $A.y -eq $B.y) -or
            $A.used -gt $B.avail) { 
            continue;
        }
        $PairCount++;
    }
}

Write-Output "Part 1: ${PairCount}";
