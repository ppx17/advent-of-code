Param(
    [string]$InputFile = '../input/input-day1.txt'
);

$Instructions = ((Get-Content $InputFile -Raw) -Split ", ");

# Navigator from 2017 - day22
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

    [string[]]Move([Int32]$Distance = 1) {
        $History = [System.Collections.ArrayList]@();
        for($i=0;$i -lt $Distance; $i++) {
            $this.X += ($this.DirX);
            $this.Y += ($this.DirY);
            [void]$History.Add(("{0}:{1}" -f $this.X, $this.Y));
        }
        return $History;
    }
}

$Navigator = [Navigator]::new(0, 0);

$LocationHistory = [System.Collections.ArrayList]@();
$BunnyHQ = $null;

Foreach($Instruction in $Instructions) {
    $Dir = $Instruction[0];
    $Distance = ([Int32]$Instruction.Substring(1));

    if($Dir -eq "R") {
        $Navigator.TurnRight();
    }else{
        $Navigator.TurnLeft();
    }

    $MoveHistory = $Navigator.Move($Distance);

    if($null -eq $BunnyHQ) {
        $MoveHistory | ForEach-Object {
            if($_ -in $LocationHistory) {
                $BunnyHQ = $_;
            }
            [void]$LocationHistory.Add($_);
        }
    }

}

Write-Output ("Part 1: {0}" -f ([Math]::Abs($Navigator.X) + [Math]::Abs($Navigator.Y)));
Write-Output "Part 2: BunnyHQ is at ${BunnyHQ}";
