Param(
    [uint32]$Iterations = 50000000,
    [uint32]$StepSize = 370
);

$CircularBuffer = New-Object -TypeName "System.Collections.Generic.List[uint32]" -ArgumentList $Iterations;

$CircularBuffer.Add(0);

[uint32]$CurrentPosition = 0;

$Ms = (Measure-Command {
    for([uint32]$Length=1; $Length -le $Iterations; $Length++) {
        $CurrentPosition = (($CurrentPosition + $StepSize) % $Length) + 1;
        $CircularBuffer.Insert($CurrentPosition, $Length);
    }
}).TotalSeconds

Write-Output ("{0:N2} sec" -f $Ms);
Write-Output ($CircularBuffer[1]);
Write-Output ($CircularBuffer[(($CurrentPosition + 1) % $Length)]);