Param(
    [string]$InputFile = 'input-day22.txt',
    [int]$Bursts = 10000,
    [switch]$Part2
);

enum CellState {
    Clean;
    Weakened;
    Infected;
    Flagged
}

class CellStateHelper {
    [CellState]static CellStateFromChar([char]$Character) {
        switch($Character) {
            '.' { return [CellState]::Clean; }
            '#' { return [CellState]::Infected }
            'F' { return [CellState]::Flagged }
            'W' { return [CellState]::Weakened }
        }
        return [CellState]::Clean;
    }
}

class Grid {
    [System.Collections.Hashtable]$Grid;

    Grid() {
        $this.Grid = [System.Collections.Hashtable]@{};
    }

    [string]Key([int]$x, [int]$y) {
        return ("{0}:{1}" -f $x, $y);
    }

    [CellState]Get([Navigator]$Navigator) {
        return $this.Get($Navigator.X, $Navigator.Y);
    }

    [CellState]Get([int]$x, [int]$y) {
        $Key = $this.Key($x, $y);
        if($null -eq $this.Grid[$Key]) {
            return [CellState]::Clean;
        }
        return $this.Grid[$Key];
    }

    [void]Set([Navigator]$Navigator, [CellState]$Value) {
        $this.Set($Navigator.X, $Navigator.Y, $Value);
    }

    [void]Set([int]$x, [int]$y, [CellState]$Value) {
        $this.Grid[$this.Key($x, $y)] = $Value;
    }
}

class Navigator {
    [int]$X = 0;
    [int]$Y = 0;
    [int]$DirX = 0;
    [int]$DirY = -1; 

    Navigator($X, $Y) {
        $this.X = $X;
        $this.Y = $Y;
    }

    [void]TurnLeft() {
        if($this.DirX -eq 0) {
            $this.DirX = $this.DirY;
            $this.DirY = 0;
        }else{
            $this.DirY = -$this.DirX;
            $this.DirX = 0;
        }
    }

    [void]TurnRight() {
        if($this.DirX -eq 0) {
            $this.DirX = -$this.DirY;
            $this.DirY = 0;
        }else{
            $this.DirY = $this.DirX;
            $this.DirX = 0;
        }
    }

    [void]Reverse() {
        $this.DirX *= -1;
        $this.DirY *= -1;
    }

    [void]Move() {
        $this.X += $this.DirX;
        $this.Y += $this.DirY;
    }
}

$Grid = [Grid]::new();

$InitialState = Get-Content $InputFile;

$y = 0;
foreach($Line in $InitialState) {
    $x = 0;
    foreach($Cell in $Line.ToCharArray()) {
        $Grid.Set($x, $y, [CellStateHelper]::CellStateFromChar($Cell));
        $x++;
    }
    $y++;
}

$StartX = [Math]::Floor($InitialState[0].Length / 2);
$StartY = [Math]::Floor($InitialState.Count / 2);

$Navigator = [Navigator]::new($StartX, $StartY);

$InfectedCount = 0;
for($i = 0; $i -lt $Bursts; $i++) {
    $CurrentNode = $Grid.Get($Navigator);

    $NextState = $null;

    switch($CurrentNode) {
        ([CellState]::Clean) {
            $Navigator.TurnLeft();
            $NextState = if($Part2) { [CellState]::Weakened }else{ [CellState]::Infected };
        }
        ([CellState]::Infected) {
            $Navigator.TurnRight();
            $NextState = if($Part2) { [CellState]::Flagged; }else { [CellState]::Clean; };
        }
        ([CellState]::Weakened) {
            $NextState = [CellState]::Infected;
        }
        ([CellState]::Flagged) {
            $Navigator.Reverse();
            $NextState = [CellState]::Clean;
        }
    }

    if($NextState -eq [CellState]::Infected) {
        $InfectedCount++;
    }

    $Grid.Set($Navigator, $NextState);
    $Navigator.Move();
}

Write-Output $InfectedCount;