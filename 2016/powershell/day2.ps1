Param(
    [string]$InputFile = '../input/input-day2.txt'
);

class Navigator {
    [int]$X = 0;
    [int]$Y = 0;

    [char[][]]$Map;

    Navigator([Int32]$X, [Int32]$Y, [char[][]]$Map) {
        $this.X = $X;
        $this.Y = $Y;
        $this.Map = $Map;
    }

    [void]Move($Dir) {
        switch($Dir) {
            "U" {
                if($this.ValidCoordinate($this.X, $this.Y - 1)) {
                    $this.Y--;
                }
            }
            "D" {
                if($this.ValidCoordinate($this.X, $this.Y + 1)) {
                    $this.Y++;
                }
            }
            "L" { 
                if($this.ValidCoordinate($this.X - 1, $this.Y)) {
                    $this.X--;
                }
            }
            "R" {
                if($this.ValidCoordinate($this.X + 1, $this.Y)) {
                    $this.X++;
                }
            }
        }
    }

    [bool]ValidCoordinate($X, $Y) {
        return $X -ge 0 `
          -and $Y -ge 0 `
          -and $X -lt $this.Map[0].Length `
          -and $Y -lt $this.Map.Length `
          -and $this.Map[$Y][$X] -ne ' ';
    }

    [string]CurrentKey() {
        return $this.Map[$this.Y][$this.X];
    }
}

$Navigators = @();

$Navigators += [Navigator]::new(1, 1, @(
    @('1','2','3'),
    @('4','5','6'),
    @('7','8','9')
));

$Navigators += [Navigator]::new(1, 2, @(
    @(' ',' ','1',' ',' '),
    @(' ','2','3','4',' '),
    @('5','6','7','8','9'),
    @(' ','A','B','C',' '),
    @(' ',' ','D',' ',' ')
));

$Directions = Get-Content $InputFile;

$Part = 1;
foreach($Navigator in $Navigators) {
    $Keys = @();
    foreach($Line in $Directions) {
        foreach($KeyPress in $Line.ToCharArray()) {
            $Navigator.Move($KeyPress);
        }
        $Keys += $Navigator.CurrentKey();
    }

    Write-Output ("Part {0}: {1}" -f $Part++,($Keys -Join ""));
}