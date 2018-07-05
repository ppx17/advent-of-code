[CmdletBinding()]
Param(
    [string]$InputFile = 'input-day19.txt'
);

class Navigator {
    [array]$Grid;
    [int]$PosX = 0;
    [int]$PosY = 0;
    
    [int]$DirX = 0;
    [int]$DirY = 1;

    [int]$StepCount = 1;

    [int]$Width;
    [int]$Height;

    [System.Collections.ArrayList]$FoundLetters = [System.Collections.ArrayList]@();

    Navigator([array]$Grid, [int]$startX) {
        $this.PosX = $startX;
        $this.Grid = $Grid;
        $this.Width = $Grid[0].Length;
        $this.Height = $Grid.Length;
    }

    [string]GetPos($x, $y) {
        if($x -lt 0 -or $y -lt 0 -or $x -ge $this.Width -or $y -ge $this.Height) {
            return " ";
        }
        $gridVal = $this.Grid[$y][$x];
        return $gridVal;
    }

    [bool]CanMove($DirX, $DirY) {
        return ($this.GetPos($this.PosX + $DirX, $this.PosY + $DirY) -ne " ");
    }

    [void]SetDirection($DirX, $DirY) {
        if($DirX -ne 0 -and $DirY -ne 0) {
            Write-Error "Cannot move diagonally!";
        }
        $this.DirX = $DirX;
        $this.DirY = $DirY;
    }

    [bool]AttemptMove($DirX, $DirY) {
        
        Write-Verbose ("Attemping to move in direction {0}:{1}" -f $DirX, $DirY);
        if($this.CanMove($DirX, $DirY)) {
            $this.SetDirection($DirX, $DirY);
            $this.MoveInDirection();
            return $true;
        }
        return $false;
    }

    [bool]Move() {
        if($this.AttemptMove($this.DirX, $this.DirY)) { return $true; }
        if($this.DirX -eq 0) {
            # Attempt both X directions with no Y movement
            if($this.AttemptMove(1, 0)) { return $true; }
            if($this.AttemptMove(-1, 0)) { return $true; }
        }else{
            # Attempt both Y directions with no X movement
            if($this.AttemptMove(0, 1)) { return $true; }
            if($this.AttemptMove(0, -1)) { return $true; }
        }
        # No movement possible, end reached?!
        return $false;
    }

    [void]MoveInDirection() {
        $this.PosX = $this.PosX + $this.DirX;
        $this.PosY = $this.PosY + $this.DirY;
        $this.CheckForLetter();
        $this.StepCount++;
        Write-Verbose ("Moving to {0}:{1}" -f $this.PosX, $this.PosY);
    }

    [void]CheckForLetter() {
        if( $this.GetPos($this.PosX, $this.PosY) -Match "[A-Z]") {
            [void]$this.FoundLetters.Add($this.GetPos($this.PosX, $this.PosY));
        }
    }
}

$Grid = Get-Content $InputFile;

# Find start position
for($x=0;$x -lt $Grid[0].Length; $x++) {
    if($Grid[0][$x] -eq "|") {
        break;
    }
}

$Navigator = [Navigator]::new($Grid, $x);

while($Navigator.Move()) {
    # Keep searching...
}

Write-Output ($Navigator.FoundLetters -Join "");
Write-Output ("{0} steps" -f $Navigator.StepCount);