Param(
    [string]$InputFile = '../input/input-day11.txt'
);

$FloorContent = Get-Content $InputFile;

$Cargo = [System.Collections.ArrayList]::new();

$Distance = $FloorContent.Count - 1;
foreach($Floor in $FloorContent) {
    $CountOnFloor = ([regex]::Matches($Floor, " a ")).count;
    for($i=0; $i -lt $CountOnFloor; $i++) {
        [void]$Cargo.Add($Distance);
    }
    $Distance--;
}

Write-Output ("Part 1: {0}" -f (((($Cargo | Measure-Object -Sum).Sum - $Cargo[0]) * 2) - $Cargo[1]));

# Add four pieces of cargo to the first floor.
(1..4) | Foreach-Object {
    [void]$Cargo.Insert(0, ($FloorContent.Count - 1));
}

Write-Output ("Part 2: {0}" -f (((($Cargo | Measure-Object -Sum).Sum - $Cargo[0]) * 2) - $Cargo[1]));
