$Content = (Get-Content 'input-day11.txt') -Split ',';
$Furthest = $X = $Y = 0;
$Content | ForEach-Object {
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