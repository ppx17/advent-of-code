[CmdletBinding()]
Param(
	[string]$Stream = "197,97,204,108,1,29,5,71,0,50,2,255,248,78,254,63",
	[int]$ListSize = 256,
	[int]$Iterations = 64,
	[switch]$StreamAsIntegers
);

$BlockSize = 16;

if($StreamAsIntegers) {
	$Lengths = $Stream.Split(",") | Foreach-Object { [Convert]::ToInt32($_, 10) };
}else{
	$Lengths = $Stream.ToCharArray() | ForEach-Object { [int]$_; }
	$Lengths += @(17, 31, 73, 47, 23);
}

$MaxIndex = $ListSize - 1;
$List = (0..$MaxIndex);
$Skip = 0;
$Current = 0;

for($Iteration = 0; $Iteration -lt $Iterations; $Iteration++) {
	foreach($Length in $Lengths) {

		if($Current + $Length -gt $MaxIndex) {
			$FirstSize = ($ListSize - $Current);
			$SecondSize = $Length - $FirstSize;
			$MiddleSize = $ListSize - ($FirstSize + $SecondSize);
			$Block = (@($List | Select-Object -Last $FirstSize) + @($List | Select-Object -First $SecondSize));
			if($null -ne $Block) { [array]::Reverse($Block); }
			$List = @($Block | Select-Object -Last $SecondSize) + @($List | Select-Object -First $MiddleSize -Skip $SecondSize) + @($Block | Select-Object -First $FirstSize);
		}else{
			$Block = ($List | Select-Object -First $Length -Skip $Current);
			if($null -ne $Block) { [array]::Reverse($Block); }
			$List = @($List | Select-Object -First $Current) + $Block + @($List | Select-Object -Last ($ListSize - ($Current + $Length)));
		}

		$Current += ($Length + $Skip);
		$Current = $Current % $ListSize;
		$Skip++;

		Write-Verbose (($List | ForEach-Object { $i=0; } { if($i -eq $Current) { "[${_}]" }else{ " ${_} " }; $i++ }) -Join ",")
	}
	$Pct = [Math]::Floor(100 / $Iterations * $Iteration);
	Write-Progress -Activity "Performing hashing iterations" -Status "${Pct}% Complete:" -PercentComplete $Pct;
}
Write-Progress -Activity "Performing hashing iterations" -Completed;

Write-Verbose ("Part 1 Checksum: {0} * {1} = {2}" -f $List[0], $List[1], ($List[0] * $List[1]));

$DenseParts = [System.Collections.ArrayList]@();
for($i=0;$i -lt $ListSize; $i += $BlockSize) {
	$Block = $List | Select-Object -First $BlockSize -Skip $i;
	[void]$DenseParts.Add( ($Block | ForEach-Object { $res=0; } { $res = $res -bxor $_; } { $res; }) );
}

return (($DenseParts | ForEach-Object { ("{0:X2}" -f $_) }) -Join "");
