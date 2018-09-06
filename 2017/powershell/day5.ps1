Param(
	[string]$InputFile = '../input/input-day5.txt'
);

$digits = Get-Content $InputFile | % { [convert]::ToInt32($_, 10) }; 

$i,$steps=0;
while($true) {
	$y = $i;
	$i += $digits[$y];
	$steps++;
	#Write-Host ("Step: {0}; Reading index: {1}, has value {2}. JMP to {3}" -f $steps, $y, $digits[$y], $i);
	if($i -lt 0 -or $i -ge $digits.Length) { 
		break;
	}
	$digits[$y]++;
};

Write-Host $steps;

$digits = Get-Content $InputFile | % { [convert]::ToInt32($_, 10) }; 
$i,$steps=0;
while($true) {
	$y = $i;
	$i += $digits[$y];
	$steps++;
	if($i -lt 0 -or $i -ge $digits.Length) { 
		break;
	}
	$digits[$y] += $(if($digits[$y] -ge 3) { -1; }else{ 1; });
};
Write-Host $steps;