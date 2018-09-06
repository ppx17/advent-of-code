Param(
    [string]$InputFile="../input/input-day16.txt",
    [int]$NumDancers=16,
    [int]$Iterations=1000000000
);
$Start = Get-Date;

$Moves = (Get-Content $InputFile -Raw) -Split ",";

# The explicit string[] casting makes sure IndexOf works with strings, otherwise the result will be an object[].
$Dancers = ([string[]] $((([int][char]'a')..(([int][char]'a')+($NumDancers-1))) | ForEach-Object { [char]$_ }))
$OriginalPositions = ($Dancers -Join "");

$CompiledInstructions = [System.Collections.ArrayList]@();
foreach($Move in $Moves) {
    switch( $Move[0] ) {
        "s" {
            [void]$CompiledInstructions.Add(@("s", [int]$Move.Substring(1)));
        }
        "x" {
            $Params = $Move.Substring(1) -Split "/"
            [void]$CompiledInstructions.Add(@("x", [int]$Params[0], [int]$Params[1]));
        }
        "p" {
            $Params = $Move.Substring(1) -Split "/";
            [void]$CompiledInstructions.Add(@("p", $Params[0], $Params[1]));
        }
    }
}

$PositionsPerIteration = [System.Collections.ArrayList]@();
for($i=0; $i -lt $Iterations; $i++) {
    foreach($Instruction in $CompiledInstructions) {
        switch( $Instruction[0] ) {
            "s" {
                $SpinSize = $Instruction[1]
                # The explicit string[] casting makes sure IndexOf works with strings, otherwise the result will be an object[].
                $Dancers = ($Dancers[-$SpinSize..-1] + $Dancers[0..($Dancers.Count-$SpinSize-1)]);
            }
            "x" {
                $tmp = $Dancers[[int]$Instruction[1]];
                $Dancers[[int]$Instruction[1]] = $Dancers[[int]$Instruction[2]];
                $Dancers[[int]$Instruction[2]] = $tmp;
            }
            "p" {
                $pos1 = $Dancers.IndexOf($Instruction[1]);
                $pos2 = $Dancers.IndexOf($Instruction[2])
                $tmp = $Dancers[$pos1];
                $Dancers[$pos1] = $Dancers[$pos2];
                $Dancers[$pos2] = $tmp;
            }
        }
    }
    $Positions = ($Dancers -Join "");
    [void]$PositionsPerIteration.Add($Positions);
    if($i -eq 0) { 
        Write-Output "Part 1: ${Positions}";
        Write-Output ("{0:N2} ms" -f ((Get-Date) - $Start).TotalMilliSeconds);
    }elseif($Positions -eq $OriginalPositions) { 
        $Left = $Iterations % ($i + 1);
        Write-Output ("Part 2: {0}" -f $PositionsPerIteration[$Left - 1]);
        Write-Output ("{0:N2} ms" -f ((Get-Date) - $Start).TotalMilliSeconds);
        break;
    }
}