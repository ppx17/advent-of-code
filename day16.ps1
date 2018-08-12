Param(
    [string]$MoveText,
    [int]$NumDancers = 16,
    [int]$Iterations=1
);

if([string]::IsNullOrEmpty($MoveText)) {
    $MoveText = (Get-Content "input-day16.txt" -Raw);
}
$Moves = $MoveText -Split ",";

# The explicit string[] casting makes sure IndexOf works with strings, otherwise the result will be an object[].
$Dancers = ([string[]] $((([int][char]'a')..(([int][char]'a')+($NumDancers-1))) | ForEach-Object { [char]$_ }))
$DancersOriginal = $Dancers.Clone();

function GetParams($Instruction) {
    $Instruction.Substring(1) -Split "/";
}

$CompiledInstructions = [System.Collections.ArrayList]@();
foreach($Move in $Moves) {
    switch( $Move[0] ) {
        "s" {
            [void]$CompiledInstructions.Add(@("s", [int]$Move.Substring(1)));
        }
        "x" {
            $Params = GetParams($Move);
            [void]$CompiledInstructions.Add(@("x", [int]$Params[0], [int]$Params[1]));
        }
        "p" {
            $Params = GetParams($Move);
            [void]$CompiledInstructions.Add(@("p", $Params[0], $Params[1]));
        }
    }
}

foreach($Instruction in $CompiledInstructions) {
    switch( $Instruction[0] ) {
        "s" {
            $SpinSize = $Instruction[1]
            # The explicit string[] casting makes sure IndexOf works with strings, otherwise the result will be an object[].
            $Dancers = ($Dancers[-$SpinSize..-1] + $Dancers[0..($Dancers.Count-$SpinSize-1)]);;
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

$Map = [int[]]::new($DancersOriginal.Count);

Write-Output ($DancersOriginal -Join "");
for($i=0;$i -lt $DancersOriginal.Count; $i++) {
    $Map[$i] = $Dancers.IndexOf($DancersOriginal[$i]);
}
Write-Output ($Map -Join " ");


$Dancers = $DancersOriginal.Clone();
$MapTime = (Measure-Command {
for($Iteration=0;$Iteration -lt $Iterations; $Iteration++) {
    $Tmp = $Dancers.Clone();
    for($i=0;$i -lt $Map.Length; $i++) {
        $Dancers[$Map[$i]] = $Tmp[$i];
    }
}
}).TotalMilliseconds;

Write-Output ("Runtime with map: {0} ms" -f $MapTime)

Write-Output ($Dancers -Join "");