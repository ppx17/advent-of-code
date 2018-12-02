Param(
    [string]$InputFile = '../input/input-day2.txt'
);

$Ids = (Get-Content $InputFile);

$Twos = $Threes = 0;

foreach($Id in $Ids) {
    $Counts = $Id.ToCharArray() | Group-Object | Select-Object -ExpandProperty Count;
    if($Counts -contains 2) {
        $Twos++;
    }
    if($Counts -contains 3) {
        $Threes++;
    }
}

$Part1 = $Twos * $Threes;

Write-Output "Part 1: ${Part1}";

Class Part2Solver {
    [int]difference([string]$First, [string]$Second) {
        $diffCount = 0;
        for($i=0; $i -lt $First.Length; $i++) {
            if($First[$i] -ne $Second[$i]) {
                $diffCount++;
            }
        }
        return $diffCount;
    }

    [string]common([string]$First, [string]$Second) {
        $Result = "";
        for($i=0; $i -lt $First.Length; $i++) {
            if($First[$i] -eq $Second[$i]) {
                $Result += $First[$i];
            }
        }
        return $Result;
    }

    [string]Solve([string[]] $Ids) {
        for($fi = 0; $fi -lt $Ids.Length; $fi++) {
            for($si = $fi + 1; $si -lt $Ids.Length; $si++) {
                $Difference = $this.difference($Ids[$fi], $Ids[$si]);
                if($Difference -eq 1) {
                    return $this.common($Ids[$fi], $Ids[$si]);
                }
            }
        }
        return $null;
    }
}

$Solver = [Part2Solver]::new();
$Part2 = $Solver.Solve($Ids);
Write-Output ("Part2: ${Part2}");
