Param(
    [int]$FavoriteNumber = 1364,
    [int]$TargetX = 31,
    [int]$TargetY = 39
);

Class Room
{
    [int]$FavoriteNumber;


    Room([int]$FavoriteNumber) {
        $this.FavoriteNumber = $FavoriteNumber;
    }

    [int]SparseBitcount([int]$n) {
        $count = 0;
        while ($n -ne 0) {
            $count++;
            $n = $n -band ($n - 1);
        }
        return $count;
    }

    [bool]IsWall($x, $y) {
        $z = $x*$x + 3*$x + 2*$x*$y + $y + $y*$y + $this.FavoriteNumber;
        return $this.SparseBitcount($z) % 2 -eq 1;
    }
}

Class AStarNode {
    [AStarNode]$Parent;
    [int]$X;
    [int]$Y;

    [int]$F;
    [int]$G;
    [int]$H;

    AStarNode([int]$X, [int]$Y) {
        $this.X = $X;
        $this.Y = $Y;
    }
}
# Implementation of the A* algorithm: https://en.wikipedia.org/wiki/A*_search_algorithm
Class AStar {
    [System.Collections.ArrayList]$Open;
    [System.Collections.ArrayList]$Closed;

    [Room]$Room;
    [int]$TargetX;
    [int]$TargetY;
    [int]$MaxG;

    AStar([Room]$Room, $TargetX, $TargetY) { 
        $this.Room = $Room;
        $this.TargetX = $TargetX;
        $this.TargetY = $TargetY;
        $this.MaxG = -1;
    }

    [AStarNode]Run([AStarNode]$InitialNode) {
        $this.Open = [System.Collections.ArrayList]::new();
        $this.Closed = [System.Collections.ArrayList]::new();
        [void]$this.Open.Add($InitialNode);
        while($this.Open.Count -gt 0) {
            $Q = $this.Open | Sort-Object -Property F | Select-Object -First 1;
            $this.Open.Remove($Q);

            $Successors = $this.CreateSuccessors($Q);

            foreach($Successor in $Successors) {
                $Successor.H = $this.DistanceToTarget($Successor);
                if($Successor.H -eq 0) {
                    return $Successor;
                }
                $Successor.G = $Q.G + 1;
                $Successor.F = $Successor.G + $Successor.H;

                if($this.MaxG -ge 0 -and $this.MaxG -lt $Successor.G) {
                    continue;
                }
                $OpenEqual = $this.Open | Where-Object {$_.X -eq $Successor.X -and $_.Y -eq $Successor.Y -and $_.F -lt $Successor.F }
                if($OpenEqual.Count -gt 0) {
                    continue;
                }
                $ClosedEqual = $this.Closed | Where-Object {$_.X -eq $Successor.X -and $_.Y -eq $Successor.Y -and $_.F -lt $Successor.F }
                if($ClosedEqual.Count -gt 0) {
                    continue;
                }
                [void]$this.Open.Add($Successor);
            }

            [void]$this.Closed.Add($Q);
        }

        return $null;
    }

    [AStarNode[]]CreateSuccessors([AStarNode]$Q) {
        $Result = [System.Collections.ArrayList]::new();
        foreach($OffsetX in @(-1,1)) {
            $N = $this.CreateSuccessor($Q, $OffsetX, 0);
            if($null -ne $N) {
                [void]$Result.Add($N);
            }
        }
        foreach($OffsetY in @(-1,1)) {
            $N = $this.CreateSuccessor($Q, 0, $OffsetY);
            if($null -ne $N) {
                [void]$Result.Add($N);
            }
        }
        return $Result;
    }

    [AStarNode]CreateSuccessor([AStarNode] $Q, $OffsetX, $OffsetY) {
        $Node = [AStarNode]::new($Q.X + $OffsetX, $Q.Y + $OffsetY);

        if($this.Room.IsWall($Node.X, $Node.Y)) {
            return $null;
        }

        $Node.Parent = $Q;
        return $Node;
    }

    [int]DistanceToTarget([AStarNode] $Q) {
        return [Math]::Abs($Q.X - $this.TargetX) + [Math]::Abs($Q.Y - $this.TargetY);
    }
}

$Room = [Room]::new($FavoriteNumber);

function AStarTo($TargetX, $TargetY, $MaxG) {
    $AStar = [AStar]::new($Room, $TargetX, $TargetY);
    if($MaxG -ne $null) {
        $AStar.MaxG = $MaxG;
    }
    $InitialNode = [AStarNode]::new(1, 1);

    $EndNode = $AStar.Run($InitialNode);

    $Steps = 0;
    while($null -ne $EndNode.Parent) {
        $Steps++;
        $EndNode = $EndNode.Parent;
    }
    return $Steps;
}

$Steps = AStarTo -TargetX $TargetX -TargetY $TargetY -MaxG $MaxG;

Write-Output "Part 1: ${Steps}";

$MaxDistance = 50;
$Count = 0;
for($x=0; $x -lt 51; $x++) {
    for($y=0; $y -lt 51; $y++) {
        Write-Host -NoNewline ("`r{0:N2}%" -f 100 * (($x*51 + $y)/(51*51)));
        if($Room.IsWall($x, $y)) {
            continue;
        }
        if(($x + $y) -gt $MaxDistance) {
            continue;
        }
        $Steps = AStarTo -TargetX $x -TargetY $y -MaxG $MaxDistance;
        if($Steps -gt $MaxDistance) {
            continue;
        }
        $Count++;
    }
}

Write-Output "Part 1: ${Count}";

# 117 too low
# 251 too high
# 223 incorrect