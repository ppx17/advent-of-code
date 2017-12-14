$Content = Get-Content "input-day2.ps1";
$sum=0;
$Content | %{ $h=0;$l=[int]::MaxValue; $_ -Split '\s+' | % { $x = [convert]::ToInt32($_, 10);$h=[math]::max($x,$h);$l=[math]::min($x,$l); }; $sum += ($h-$l); }
#return $sum;

$sum=0;
$Content | %{ 
	$digits = $_ -Split '\s+' | % { [convert]::ToInt32($_, 10);	}; 
	for($x=0;$x -lt $digits.length;$x++) {
		for($y=$x+1;$y -lt $digits.length;$y++) {
			$res1 = $digits[$x]/$digits[$y];
			$res2 = $digits[$y]/$digits[$x];
			if([math]::floor($res1) -eq $res1) {
				$sum+=$res1;
			}elseif([math]::floor($res2) -eq $res2) {
				$sum+=$res2;
			}
		}
	}
}
return $sum;