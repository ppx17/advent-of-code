Param(
    [string]$InputFile = 'input-day8.txt',
    [int]$Width = 50,
    [int]$Height = 6
);

$Display = New-Object Char[][] $Height,$Width;

for($y=0;$y -lt $Height; $y++) {
    for($x=0; $x -lt $Width; $x++) {
        $Display[$y][$x] = '.';
    }
}

foreach($Instruction in (Get-Content $InputFile)) {
    if($Instruction -Match "rect ([0-9]+)x([0-9]+)") {
        for($y = 0; $y -lt $Matches[2]; $y++) {
            for($x = 0; $x -lt $Matches[1]; $x++) {
                $Display[$y][$x] = '#';
            }
        }
    }

    if($Instruction -Match "rotate column x=([0-9]+) by ([0-9]+)") {
        $x = $Matches[1];
        $Distance = $Matches[2] % $Height;
        for($i=0;$i -lt $Distance; $i++) {
            $Tmp = $Display[$Height - 1][$x];
            for($y = $Height -2; $y -ge 0; $y--) {
                $Display[$y + 1][$x] = $Display[$y][$x];
            }
            $Display[0][$x] = $Tmp;
        }
    }

    if($Instruction -Match "rotate row y=([0-9]+) by ([0-9]+)") {
        $y = $Matches[1];
        $Distance = $Matches[2] % $Width;
        for($i=0;$i -lt $Distance; $i++) {
            $Tmp = $Display[$y][$Width - 1];
            for($x = $Width -2; $x -ge 0; $x--) {
                $Display[$y][$x + 1] = $Display[$y][$x];
            }
            $Display[$y][0] = $Tmp;
        }
    }


}

Write-Output ("Part 1: " + (@($Display | Foreach-Object {$_}) | Where-Object { $_ -eq '#' } | Measure-Object).Count)

# Display grid
Write-Host "Part 2:";
for($y=0;$y -lt $Height; $y++) {
    Write-Host ($Display[$y] -Join "");
}