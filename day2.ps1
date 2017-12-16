$Content = Get-Content "input-day2.txt";
$sum=0;
$Content | %{ $h=0;$l=[int]::MaxValue; $_ -Split '\s+' | % { $x = [convert]::ToInt32($_, 10);$h=[math]::max($x,$h);$l=[math]::min($x,$l); }; $sum += ($h-$l); }
#return $sum;

$sum=0;
$Content | %{ 
	$digits = $_ -Split '\s+' | % { [convert]::ToInt32($_, 10);	}; 
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

return $sum;