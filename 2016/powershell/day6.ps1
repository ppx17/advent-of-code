Param(
    [string]$InputFile = '../input/input-day6.txt'
);

$Messages = (Get-Content $InputFile);

[char[][]]$ColumnCharacters = New-Object char[][] $Messages[0].Length,$Messages.Length;

for($i=0; $i -lt $Messages[0].Length; $i++) {
    $x=0;
    foreach($Message in $Messages) {
        $ColumnCharacters[$i][$x] = $Message[$i];
        $x++;
    }
}

$Results = [string[]]($ColumnCharacters | Foreach-Object { 
    $_ | Group-Object | Sort-Object -Descending Count  | Select-Object -First 1 | Select-Object -ExpandProperty Name;
});

Write-Output ("Part 1: {0}" -f ($Results -Join ""));

$Results = [string[]]($ColumnCharacters | Foreach-Object { 
    $_ | Group-Object | Sort-Object Count  | Select-Object -First 1 | Select-Object -ExpandProperty Name;
});

Write-Output ("Part 2: {0}" -f ($Results -Join ""));