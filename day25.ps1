Param(
    [string]$InputFile = 'input-day25.txt'
);

$State = [char]"A";

$RuleFile = (Get-Content $InputFile -Raw);

$Rules = @{};

if($RuleFile -Match "Begin in state (?<State>[A-Z]).") {
    $State = [char]$Matches.State;
}else{
    Write-Error "Begin state not found.";
    Exit 1;
}
if($RuleFile -Match "Perform a diagnostic checksum after (?<Steps>[0-9]+) steps.") {
    $Steps = [Convert]::ToInt32($Matches.Steps);
}else{
    Write-Error "Steps not found";
    Exit 2;
}

$TapeSize = ($Steps * 2) + 1;
$Center = [Math]::Floor($TapeSize / 2);

$Tape = New-Object bool[] $TapeSize;
$Pos = $Center;

$RegexPattern = "In state (?<InState>[A-Z]):\s+If.*is 0:\s+.*value (?<ZeroVal>[0-1]).\s+.*the (?<ZeroDir>(left|right)).\s+.*state (?<ZeroNextState>[A-Z]).\s+If.*is 1:\s+.*value (?<OneVal>[0-1]).\s+.*the (?<OneDir>(left|right)).\s+.*state (?<OneNextState>[A-Z]).";

$Rules = [System.Collections.Hashtable]@{};

$RuleFile | Select-String -Pattern $RegexPattern -AllMatches | Select-Object -ExpandProperty Matches | ForEach-Object {
    $Rules.Add(([char]$_.Groups[3].Value), @{
        $false = @{
            "write"= ($_.Groups[4].Value -eq "1");
            "move"= $(if($_.Groups[5].Value -eq "right") { 1 }else{ -1 });
            "next"=[char]$_.Groups[6].Value
        };
        $true = @{
            "write"=($_.Groups[7].Value -eq "1");
            "move"= $(if($_.Groups[8].Value -eq "right") { 1 }else{ -1 });
            "next"=[char]$_.Groups[9].Value
        }
    });
}

Write-Output "Applying steps...";
$count = 0;
for($i=0; $i -lt $Steps; $i++) {
    $Rule = $Rules[$State][$Tape[$Pos]];
    if($Tape[$Pos] -ne $Rule['write']) {
        if($Rule['write']) { $count++; }else{ $count--; }
    }
    $Tape[$Pos] = $Rule['write'];
    $Pos += $Rule['move'];
    if($Pos -lt 0 -or $Pos -gt $TapeSize) {
        Write-Error "Tape too short, current pos: ${Pos}";
        Exit 1;
    }
    $State = $Rule['next'];
}

Write-Output "Part 1: ${count}";

# 4227670 too high