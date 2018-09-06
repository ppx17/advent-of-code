Param(
	[string]$InputFile = '../input/input-day2.txt'
);
$Content = Get-Content $InputFile;
$sum=0;
$Content | ForEach-Object { $h=0;$l=[int]::MaxValue; $_ -Split '\s+' | ForEach-Object { $x = [convert]::ToInt32($_, 10);$h=[math]::max($x,$h);$l=[math]::min($x,$l); }; $sum += ($h-$l); }
Write-Output "Part 1 methdo 1: ${sum}";

$sum = 0; $Content | ForEach-Object { $Stat = [int[]]($_ -Split '\s+') | Measure-Object -Maximum -Minimum; $sum += ($Stat.Maximum - $Stat.Minimum); }
Write-Output "Part 1 method 2: ${sum}";

$sum=0;
$Content | ForEach-Object{ 
	$digits = $_ -Split '\s+' | ForEach-Object { [convert]::ToInt32($_, 10); }; 
	for($x=0;$x -lt $digits.length;$x++) {
		for($y=$x+1;$y -lt $digits.length;$y++) {
			if($digits[$x] % $digits[$y] -eq 0) {
				$sum+=$digits[$x] / $digits[$y];
			}elseif($digits[$y] % $digits[$x] -eq 0) {
				$sum+=$digits[$y] / $digits[$x];
			}
		}
	}
}

Write-Host "Part 2 method 1: ${sum}";

$sum=0;
$Content | ForEach-Object{ 
	$digits = $_ -Split '\s+' | ForEach-Object { [convert]::ToInt32($_, 10); }; 
	for($x=0;$x -lt $digits.length;$x++) {
		for($y=0;$y -lt $digits.length;$y++) {
			if($x -eq $y) { continue; }
			if($digits[$x] % $digits[$y] -eq 0) {
				$sum+=$digits[$x] / $digits[$y];
			}
		}
	}
}

Write-Host "Part 2 method 2: ${sum}";

$sum=0;
$Content | ForEach-Object{ 
	$digits = $_ -Split '\s+' | ForEach-Object { [convert]::ToInt32($_, 10); }; 
	for($x=0;$x -lt $digits.length;$x++) {
		for($y=0;$y -lt $digits.length;$y++) {
			if($digits[$x]/ $digits[$y] -eq 1) { continue; }
			if($digits[$x] % $digits[$y] -eq 0) {
				$sum+=$digits[$x] / $digits[$y];
			}
		}
	}
}

Write-Host "Part 2 method 3: ${sum}";


$sum=0;
$Content | ForEach-Object{ 
	$digits = [int[]]($_ -Split '\s+');
	for($x=0;$x -lt $digits.length;$x++) {
		for($y=0;$y -lt $digits.length;$y++) {
			if($digits[$x]/ $digits[$y] -eq 1) { continue; }
			if($digits[$x] % $digits[$y] -eq 0) {
				$sum+=$digits[$x] / $digits[$y];
			}
		}
	}
}

Write-Host "Part 2 method 4: ${sum}";
