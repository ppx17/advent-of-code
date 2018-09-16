Param(
    $HashInput = "oundnydw",
    $RowCount = 128,
    $InMapFile = "../input/map-day14.txt",
    $OutMapFile
);

if($InMapFile -ne $null) {
    $Rows = Get-Content $InMapFile;
}else{
    $Rows = [System.Collections.ArrayList]@();

    For($i=0; $i -lt $RowCount; $i++) {
        $Tohash = ("{0}-{1}" -f $HashInput, $i);
        $Hash = .\day10.ps1 -Stream $ToHash;

        $sb = [System.Text.StringBuilder]::new();
        foreach($Character in $Hash.ToCharArray()) {
            [void]$sb.Append([Convert]::ToString([Convert]::ToInt32($Character, 16), 2).PadLeft(4, "0"));
        }
        [void]$Rows.Add($sb.ToString());
        $Pct = [Math]::Floor(100 / $RowCount * $i);
        Write-Progress -Activity "Hashing rows" -Status "${Pct}% Complete:" -PercentComplete $Pct;
    }
    Write-Progress -Activity "Hasing rows" -Completed;
}

$TotalSections = 0;

$Rows | ForEach-Object { $TotalSections += $_.Split("1").GetUpperBound(0); }

Write-Output $TotalSections;

if($null -ne $OutMapFile) {
    $Rows | Set-Content $OutMapFile;
}

class GridGroupCounter
{
    [System.Collections.ArrayList]$Mapped
    [array]$Grid
    [int]$Width
    [int]$Height

    GridGroupCounter([array]$Grid) { 
        $this.Grid = $Grid;
        $this.Height = $Grid.Count;
        $this.Width = $Grid[0].Length;
        $this.Mapped = [System.Collections.ArrayList]@();
    }
        
    [int]GetId([int]$Row, [int]$Col) {
        return $Row*$this.Width+$Col;
    }
    [bool]IsMapped([int]$Row, [int]$Col) {
        return ($this.Mapped.IndexOf( ($this.GetId($Row, $Col)) )  -gt -1);
    }

    [void]MarkMapped([int]$Row, [int]$Col) {
        [void]$this.Mapped.Add( ($this.GetId($Row,$Col)) ) ;
    }

    [bool]IsInGrid([int]$Row, [int]$Col) {
        return $Row -ge 0 -and $Col -ge 0 -and $Row -lt $this.Height -and $Col -lt $this.Width;
    }

    [bool]CheckCell([int]$Row, [int]$Col) {
        if( -not ($this.IsInGrid($Row, $Col))) {
            return $false;
        }
        if($this.Grid[$Row][$Col] -eq "0") {
            return $false;
        }
        if( $this.IsMapped($Row, $Col)) {
            return $false;
        }
        $this.Map($Row, $Col);
        return $true;
    }

    [void]Map([int]$Row, [int]$Col) {
        $this.MarkMapped($Row, $Col);
        # check surroundings
        $this.CheckCell($Row - 1, $Col);
        $this.CheckCell($Row + 1, $Col);
        $this.CheckCell($Row, $Col - 1);
        $this.CheckCell($Row, $Col + 1); 
    }

    [int] CountGroups() {
        $Groups = 0;
        for([int]$y=0;$y -lt $this.Height; $y++) {
            for([int]$x=0; $x -lt $this.Width; $x++) {
                $x = [int]$x;
                $y = [int]$y;
                if($this.CheckCell($x, $y)) {
                    $Groups++;
                }
            }
        }
        return $Groups;
    }
}

$Counter = [GridGroupCounter]::new($Rows);

$GroupCount = $Counter.CountGroups();

Write-Output "Found ${GroupCount} groups.";