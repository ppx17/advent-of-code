Param(
    [Uint32]$StartGeneratorA=289,
    [Uint32]$StartGeneratorB=629,
    [Uint32]$Iterations=5000000
);

$FactorA = [Uint32]16807;
$FactorB = [Uint32]48271;

$MultipleA = [Uint32]4;
$MultipleB = [Uint32]8;

$Modulus = [Uint32]2147483647;

$A = $StartGeneratorA;
$B = $StartGeneratorB;

$mask = [UInt32]0xFFFF;

$JudgeCount = 0;
for($i=0; $i -lt $Iterations; $i++) {
    do {
        $A = ([Uint32] (($A * $FactorA) % $Modulus));
    }while($A % $MultipleA -ne 0);

    do {
        $B = ([Uint32] (($B * $FactorB) % $Modulus));
    }while($B % $MultipleB -ne 0);

    # In order to compare only the 16 lowest bits of a 32 bit integer ...
    # Method 1
    # ...shift the highest 16 bits off the end and compare the result
    if(($A -shl 16) -eq ($B -shl 16)) {
       $JudgeCount++;
    }

    # Method 2
    # Mask off the highest bits and compare the lowest
    #if( ($A -band $mask) -eq ($B -band $mask)) {
    #    $JudgeCount++;
    #}
}

Write-Output $JudgeCount;