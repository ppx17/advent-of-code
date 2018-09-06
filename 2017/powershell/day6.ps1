Param(
	[string]$InputFile = '../input/input-day6.txt'
);
$Banks = (Get-Content $InputFile) -Split '\s+' | Foreach-Object { [convert]::ToInt32($_, 10) }; ;
[System.Collections.ArrayList]$KnownDistributions = @();

$cycle = 1;
while($true) {
	$i = $Banks.IndexOf(($Banks | Sort -Desc | Select -First 1));
	$v = $Banks[$i];
	$Banks[$i] = 0;
	(1..$v) | ForEach-Object { 
		$Banks[($i + $_) % $Banks.Length]++;
	}
	$id = $($($Banks | % { $_.ToString() }) -Join ",");
	if($KnownDistributions.IndexOf($id) -gt -1 ) {
		break;
	}
	$KnownDistributions += $id;
	$cycle++;
}

Write-Host $cycle, ($cycle - ($KnownDistributions.IndexOf($id) + 1));