$JumpOffsets = Get-Content '../input/input-day5-lytse.txt' | % { [convert]::ToInt32($_, 10); }
$Position = 0;
$i = 0;

while ($true) {
	$Value = $Position;
	$Position += $JumpOffsets[$Position];
	if ($JumpOffsets[$Value] -ge 3) {
		$JumpOffsets[$Value]--;
	}else{
	    $JumpOffsets[$Value]++;
	}
	
	$i++;
	
	if (($Position -lt 0) -or ($Position -ge $JumpOffsets.Length)) {
		return $i;
	}
} 
