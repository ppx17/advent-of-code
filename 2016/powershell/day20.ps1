Param(
    [string]$InputFile = '../input/input-day20.txt'
);

$Rules = Get-Content $InputFile | ForEach-Object { ,([uint32[]]$_.Split('-')); } | Sort-Object { $_[0] }

[uint32]$Min = 0;
foreach($Rule in $Rules) {
    if($Rule[0] -gt $Min) {
        break;
    }
    $Min = $Rule[1] + 1;
}

Write-Output "Part 1: ${Min}";

[uint32]$AllowedCount = 0;
[uint32]$LastAllowed = 0;
foreach($Rule in $Rules) {
    if($Rule[0] -gt $LastAllowed) {
        $AllowedCount += $Rule[0] - $LastAllowed;
    }
    if($Rule[1] -gt $LastAllowed) {
        $LastAllowed = [Math]::Min($Rule[1] + 1, [uint32]::MaxValue);
    }
}
$AllowedCount += [UInt32]::MaxValue - $LastAllowed;
Write-Output "Part 2: ${AllowedCount}";
