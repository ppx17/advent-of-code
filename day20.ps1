[CmdletBinding()]
Param(
    [string]$InputFile = 'input-day20.txt',
    [int]$Iterations = 300,
    [int]$ReportEvery = 10,
    [switch]$Collide
);

$Matrix = [System.Collections.ArrayList]@();

$RawData = Get-Content $InputFile -Raw;

$DataSet = [Regex]::Matches($RawData, "p=<(-?[0-9]+),(-?[0-9]+),(-?[0-9]+)>, v=<(-?[0-9]+),(-?[0-9]+),(-?[0-9]+)>, a=<(-?[0-9]+),(-?[0-9]+),(-?[0-9]+)>");

$DataSet = $DataSet | Foreach-Object { 
    $Row = $_.Groups | Select-Object -Skip 1 | Select-Object -ExpandProperty Value | ForEach-Object { [int]$_; };
    [void]$Matrix.Add($Row); 
}

for($i=0;$i -lt $Iterations; $i++) {
    for($p=0;$p -lt $Matrix.Count; $p++) {
        # Velocity += acceleration
        $Matrix[$p][3] += $Matrix[$p][6];
        $Matrix[$p][4] += $Matrix[$p][7];
        $Matrix[$p][5] += $Matrix[$p][8];

        # Position += velocity
        $Matrix[$p][0] += $Matrix[$p][3];
        $Matrix[$p][1] += $Matrix[$p][4];
        $Matrix[$p][2] += $Matrix[$p][5];
    }
    if($Collide) {
        for($p1=0;$p1 -lt $Matrix.Count; $p1++) {
            $Removals = [System.Collections.ArrayList]@();
            for($p2=$p1+1;$p2 -lt $Matrix.Count; $p2++) {
                if($Matrix[$p1][0] -eq $Matrix[$p2][0] -and 
                    $Matrix[$p1][1] -eq $Matrix[$p2][1] -and 
                    $Matrix[$p1][2] -eq $Matrix[$p2][2]) {
                        [void]$Removals.Add($p2);
                }
            }
            if($Removals.Count -gt 0) {
                for($x=$Removals.Count -1; $x -ge 0; $x--) {
                    $Matrix.RemoveAt($Removals[$x]);
                }
                $Matrix.RemoveAt($p1);
                # Since we just removed our current item we must reset p1 to prevent skipping the item
                # that dropped into this position.
                $p1--; 
            }
        }
    }

    if(($i % $ReportEvery) -eq 0) {      
        $LowestDistance = [Int]::MaxValue;
        $ClosestParticle = $null;
        for($p=0;$p -lt $Matrix.Count; $p++) {
            $Distance = [Math]::Abs($matrix[$p][0]) + [Math]::Abs($matrix[$p][1]) + [Math]::Abs($matrix[$p][2]);
            if($Distance -lt $LowestDistance) {
                $LowestDistance = $Distance;
                $ClosestParticle = $p;
            }
        }
        Write-Output ("Iteration: {0}`tClosest particle: {1}`tClosest distance: {2}`tParticles left: {3}" -f $i, $ClosestParticle, $LowestDistance, $Matrix.Count);
    }
}
