Param(
    [string]$InputFile = '../input/input-day3.txt'
);

$Lines = Get-Content $InputFile;

$IntegerGrid = ($Lines | ForEach-Object { ,([int[]]@( ($_.TrimStart() -split "\s+"))) })

$Triangles =  $IntegerGrid | Foreach-Object { ,([int[]]($_ | Sort-Object)); } ;

$ValidTriangles = $Triangles | Where-Object { $_[0] + $_[1] -gt $_[2]; }

Write-Output ("Part 1: {0}" -f ($ValidTriangles | Measure-Object).Count);

$Triangles = [System.Collections.ArrayList]@();

for($Y = 0; $Y -lt $IntegerGrid.Count; $Y+=3) {
    for($X = 0; $X -lt $IntegerGrid[0].Count; $X++) {
        [void]$Triangles.Add(
            ([int[]](@($IntegerGrid[$Y][$X], $IntegerGrid[$Y+1][$X], $IntegerGrid[$Y+2][$X]) | Sort-Object))
        );
    }
}

$ValidTriangles = $Triangles | Where-Object { $_[0] + $_[1] -gt $_[2]; }

Write-Output ("Part 2: {0}" -f ($ValidTriangles | Measure-Object).Count);