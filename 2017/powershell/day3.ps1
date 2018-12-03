# Day 3
Param(
	[int]$Element=361527
);

# Calculate rings
$RingSize = 0;
$TotalSize = 1;
$Rings = 0;

do {
	$RingSize += 8;
	$TotalSize += $RingSize;
	$Rings++;
} while($TotalSize -lt $Element);
$SideLength = [math]::sqrt($TotalSize);

$Offset = $TotalSize - $Element;

if($Offset -lt $SideLength) {
	$X = 0; # Bottom row
} elseif($Offset -lt ($SideLength*2)-1) {
	$X = $SideLength; # Left row
} elseif($Offset -lt ($SideLength*3)-2) {
	$X = ($SideLength - 1)*2; # Top row
} else {
	$X = ($SideLength - 1) * 3;  # Right row
}

$Distance = $Rings + [math]::abs(($SideLength - 1)/2 - ($Offset - $X));

Write-Output "Part 1: ${Distance}";

## Part two

Class Matrix {
	[System.Collections.HashTable]$data = @{};

	[int]Read([int]$x, [int]$y) {
		$Key = $this.Key($x, $y);
		if($this.data[$Key]) {
			return $this.data[$Key];
		}else{
			return 0;
		}
	}
	
	[void]Write([int]$x, [int]$y, [int]$val) {
		$this.data[$this.Key($x, $y)] = $val;
	}
	
	[int]SumOfNeighbors([int]$x, [int]$y) {
		$sum = 0;
		foreach($addX in (-1..1)) {
			foreach($addY in (-1..1)) {
				if($addX -eq 0 -and $addY -eq 0) { continue; }
				$sum += $this.Read($x + $addX, $y + $addY);
			}
		}
		return $sum;
	}

	[string]Key([int]$x, [int]$y) {
		return ("{0}:{1}" -f $x, $y);
	}
}

$Matrix = New-Object Matrix;

$Matrix.Write(0, 0, 1);

$currentRing = 0;
$currentRingSize = 0;
$currentSideLength = 0;

$x,$y = 0;

while($true) {
	# Initialize new ring
	$currentRing++;
	$currentRingSize += 8;
	$currentSideLength += 2;
	
	$x++;
	# Move up
	for($i=0;$i -lt $currentSideLength; $i++) {
		$LatestEntry = $Matrix.SumOfNeighbors($x, $y);
		if($LatestEntry -gt $Element) {
			# Found the bitch!
			Write-Output "Part 2: ${LatestEntry}";
			return;
		}
		$Matrix.Write($x, $y, $LatestEntry);
		
		if($i -lt $currentSideLength - 1) {
			# Moving up
			$y--;
		}
	}
	
	$x--;
	# Move left
	for($i=0;$i -lt $currentSideLength; $i++) {
		$LatestEntry = $Matrix.SumOfNeighbors($x, $y);
		if($LatestEntry -gt $Element) {
			# Found the bitch!
			Write-Output "Part 2: ${LatestEntry}";
			return;
		}
		$Matrix.Write($x, $y, $LatestEntry);
		
		if($i -lt $currentSideLength - 1) {
			# Moving up
			$x--;
		}
	}
	
	$y++;
	# Move down
	for($i=0;$i -lt $currentSideLength; $i++) {
		$LatestEntry = $Matrix.SumOfNeighbors($x, $y);
		if($LatestEntry -gt $Element) {
			# Found the bitch!
			Write-Output "Part 2: ${LatestEntry}";
			return;
		}
		$Matrix.Write($x, $y, $LatestEntry);
		
		if($i -lt $currentSideLength - 1) {
			# Moving up
			$y++;
		}
	}
	
	$x++;
	# Move right
	for($i=0;$i -lt $currentSideLength; $i++) {
		$LatestEntry = $Matrix.SumOfNeighbors($x, $y);
		if($LatestEntry -gt $Element) {
			# Found the bitch!
			Write-Output "Part 2: ${LatestEntry}";
			return;
		}
		$Matrix.Write($x, $y, $LatestEntry);
		
		if($i -lt $currentSideLength - 1) {
			# Moving up
			$x++;
		}
	}

}