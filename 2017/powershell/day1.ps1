Param(
	[string]$InputFile = '../input/input-day1.txt'
);
$Chars = (Get-Content $InputFile -Raw).ToCharArray() | ForEach-Object { [Convert]::ToInt32($_, 10) };
for($i,$y,$y2=0;$i -lt $Chars.Length;$i++) { 
	if($Chars[($i + 1) % $Chars.Length] -eq $Chars[$i]) {
		$y += $Chars[$i];
	}
	if($Chars[($i + ($Chars.Length / 2)) % $Chars.Length] -eq $Chars[$i]) {
		$y2 += $Chars[$i];
	}
}
Write-Output "Part 1: ${y}";
Write-Output "Part 2: ${y2}";