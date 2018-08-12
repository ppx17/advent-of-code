Param(
    [int]$Iterations = 4
);

$Start = "abcdefghijklmnop".ToCharArray();
$End   = "jcobhadfnmpkglie".ToCharArray();

$Length = $Start.Length;

$Map = @();
$Start | ForEach-Object { $Map += $End.IndexOf($_); }

$MapTime = (Measure-Command {
    for($Iteration = 0; $Iteration -lt $Iterations; $Iteration++) {
        $Temp = $Start.Clone();
        for($i=0;$i -lt $Length; $i++) {
            $Temp[$Map[$i]] = $Start[$i];
        }
        $Start = $Temp;
    }
}).TotalMilliSeconds;

$IntendedIterations = 1000000000;
$MultiplicationFactor = ($IntendedIterations / $Iterations);
Write-Output ("Projected runtime: {0:N2} hours" -f (($MapTime * $MultiplicationFactor) / 3600000));

Write-Output ($Temp -Join "");