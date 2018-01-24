$Content = (gc 'input-day11.txt') -Split ',';

$X = $Y = 0;
$Furthest = 0;

$Content | % {
	switch ($_) {
		"N" { $Y++; }
		"S" { $Y--; }
		"E" { $X++; }
		"W" { $X--; }
		"NE" { $X+=0.5; $Y+=0.5; }
		"NW" { $X-=0.5; $Y+=0.5; }
		"SE" { $X+=0.5; $Y-=0.5; }
		"SW" { $X-=0.5; $Y-=0.5; }
	}
	
	$Distance = ([math]::abs($X) + [math]::abs($Y));
	
	if($Distance -gt $Furthest) { $Furthest = $Distance; }
}
Write-Host $Distance, $Furthest;