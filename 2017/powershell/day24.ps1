Param(
    [string]$InputFile = '../input/input-day24.txt'
);

Class Node {

    [int]$LeftConnector;
    [int]$RightConnector;

    Node([array]$Connectors) {
        $this.LeftConnector = $Connectors[0];
        $this.RightConnector = $Connectors[1];
    }

    [int]GetStrength() {
        return $this.LeftConnector + $this.RightConnector;
    }

    [int]GetOtherConnector([int]$OneConnector) {
        if($this.LeftConnector -eq $this.RightConnector) {
            return $this.LeftConnector;
        }elseif($this.LeftConnector -eq $OneConnector) {
            return $this.RightConnector;
        }elseif($this.RightConnector -eq $OneConnector) {
            return $this.LeftConnector;
        }else{
            Write-Error "I don't have a ${OneConnector} connector";
            return 0;
        }
    }

    [string]ToString() {
        return ("{0}/{1}" -f $this.LeftConnector, $this.RightConnector);
    }
}

class History {
    [System.Collections.ArrayList]$NodesLeft;
    [System.Collections.ArrayList]$NodesUsed;
    [int]$TotalStrength;
    [int]$FreeConnector;

    History([History]$PreviousHistory, [Node]$NextNode) {
        $this.NodesLeft = $PreviousHistory.NodesLeft.Clone();
        $this.NodesUsed = $PreviousHistory.NodesUsed.Clone();
        $this.TotalStrength = $PreviousHistory.TotalStrength;

        $this.NodesLeft.Remove($NextNode);
        $this.NodesUsed.Add($NextNode);
        $this.TotalStrength += $NextNode.GetStrength();
        $this.FreeConnector = $NextNode.GetOtherConnector($PreviousHistory.FreeConnector);
    }

    # Initial history constructor
    History([System.Collections.ArrayList]$AllNodes, [Node]$LastNode) {
        $this.NodesLeft = $AllNodes.Clone();
        $this.NodesUsed = [System.Collections.ArrayList]@();
        $this.TotalStrength = 0;
        $this.NodesLeft.Remove($LastNode);
        $this.NodesUsed.Add($LastNode);
        $this.TotalStrength += $LastNode.GetStrength();
        $this.FreeConnector = $LastNode.GetOtherConnector(0);
    }

    [array]GetNextOptions() {
        return $this.NodesLeft | Where-Object { $_.LeftConnector -eq $this.FreeConnector -or $_.RightConnector -eq $this.FreeConnector }
    }

    [int]GetLength() {
        return $this.NodesUsed.Count;
    }

    [string]ToString() {
        return ($this.NodesUsed | ForEach-Object { $_.ToString() }) -Join "--"
    }
}

$AllNodes = [System.Collections.ArrayList]@();
Get-Content $InputFile | ForEach-Object { [void]$AllNodes.Add( [Node]::new(@($_ -Split "/") ) ); };

$StartOptions = $AllNodes | Where-Object { $_.LeftConnector -eq 0 -or $_.RightConnector -eq 0 }

$AllHistories = [System.Collections.ArrayList]@();

Function Invoke-History {
    Param([History]$History);
    $NextOptions = $History.GetNextOptions();
    if($NextOptions.Count -eq 0) {
        # This is the end of a history line
        [void]$AllHistories.Add($History);
    }else{
        Foreach($NextOption in $NextOptions) {
            $NewHistory = [History]::new($History, $NextOption);
            Invoke-History -History $NewHistory;
        }
    }
}

$StartOptions |  ForEach-Object {
    $History = [History]::new($AllNodes,  $_);
    Invoke-History -History $History;
}

$Bridges = $AllHistories | Select-Object TotalStrength, `
    @{"Name"="Length"; Expression={$_.GetLength()}}, `
    @{"Name"="Representation"; Expression={$_.ToString()}}

Write-Output "Part 1:";
$Bridges | Sort-Object -Descending TotalStrength | Select-Object -First 10;

Write-Output "Part 2:";
$Bridges | Sort-Object -Descending Length,TotalStrength | Select-Object -First 10;
