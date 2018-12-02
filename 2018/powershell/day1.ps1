Param(
    [string]$InputFile = '../input/input-day1.txt'
);

$Statements = (Get-Content $InputFile) | Foreach-Object { [Int32]::Parse($_) };

$Sum = 0;
$Part1 = $Part2 = $null;
$History = [System.Collections.ArrayList]::new();
while ($null -eq $Part2) {
    $Statements | Foreach-Object { 
        $Sum += $_; 
        if ($null -eq $Part2 -and $History.IndexOf($Sum) -ge 0) {
            $Part2 = $Sum;
        }
        [void]$History.Add($Sum);
    };
    if ($null -eq $Part1) {
        $Part1 = $Sum;
    }
}

Write-Output "Part 1: ${Part1}";
Write-Output "Part 2: ${Part2}";