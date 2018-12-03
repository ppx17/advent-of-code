Param(
    [string]$InputFile = '../input/input-day3.txt'
);

class Grid {
    [byte[]]$Grid;

    [int]$Width = 1000;
    [int]$Heigth = 1000;

    [byte]$Overlap = 2;

    Grid() {
        $this.Grid = [Byte[]]::new($this.Width*$this.Heigth);
    }

    [void]Claim([int]$x, [int]$y, [int]$w, [int]$h) {
        for($loopY = $y; $loopY -lt $y+$h; $loopY++) {
            for($loopX = $x; $loopX -lt $x+$w; $loopX++) {
                $idx = $loopX * $this.Width + $loopY;
                if($this.Grid[$idx] -lt $this.Overlap) {
                    $this.Grid[$idx]++;
                }
            }
        }
    }

    [bool]IsNotOverlapping([int]$x, [int]$y, [int]$w, [int]$h) {
        for($loopY = $y; $loopY -lt $y+$h; $loopY++) {
            for($loopX = $x; $loopX -lt $x+$w; $loopX++) {
                if($this.Grid[$loopX * $this.Width + $loopY] -eq $this.Overlap) {
                    return $false;
                }
            }
        }
        return $true;
    }

    [int]OverlapCount() {
        $count = 0;
        for($i=0;$i -lt $this.Grid.Length; $i++) {
            if($this.Grid[$i] -eq $this.Overlap) { $count++; }
        }
        return $count;
    }
}

$Claims = (Get-Content $InputFile) | Foreach-Object { $null = ($_ -match "#(?<id>[0-9]+) @ (?<x>[0-9]+),(?<y>[0-9]+): (?<w>[0-9]+)x(?<h>[0-9]+)"); return $Matches; };

$Grid = [Grid]::new();
foreach($Claim in $Claims) {
    $Grid.Claim($Claim['x'], $Claim['y'], $Claim['w'], $Claim['h']);
}

Write-Output ("Part 1: " + $Grid.OverlapCount());

foreach($Claim in $Claims) {
    if($Grid.IsNotOverlapping($Claim['x'], $Claim['y'], $Claim['w'], $Claim['h'])) {
        Write-Output ("Part 2: " + $Claim['id']);
        Exit;
    }
}