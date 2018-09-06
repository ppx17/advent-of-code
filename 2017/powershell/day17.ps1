Param(
    $Iterations = 2017,
    $StepSize = 370
);

$CircularBuffer = New-Object -TypeName "System.Collections.Generic.List[int32]" -ArgumentList $Iterations;

$CircularBuffer.Add(0);

$CurrentPosition = 0;

for($Length=1; $Length -le $Iterations; $Length++) {
    $CurrentPosition = (($CurrentPosition + $StepSize) % $Length) + 1;
    $CircularBuffer.Insert($CurrentPosition, $Length);
}

Write-Output ($CircularBuffer[(($CurrentPosition + 1) % $Length)]);