Param(
    $Iterations = 50000000,
    $StepSize = 370
);

$CurrentPosition = 0;
$LastPosOne = 0;;

for($Length=1; $Length -le $Iterations; $Length++) {
    $CurrentPosition = (($CurrentPosition + $StepSize) % $Length) + 1;
    if($CurrentPosition -eq 1) {
        $LastPosOne = $Length;
    }
}

Write-Output $LastPosOne;
