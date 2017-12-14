# Day 3
Param(
	[int]$Element
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

# Full blown notation
if($Offset -lt $SideLength) {
	# Bottom row
	$X = ($SideLength - 1)/2 - $Offset;
	$Y = $Rings;
} elseif($Offset -lt ($SideLength*2)-1) {
	# Left row
	$X = -$Rings;
	$Y = ($SideLength - 1)/2 - ($Offset - $SideLength);
} elseif($Offset -lt ($SideLength*3)-2) {
	# Top row
	$X = ($SideLength - 1)/2 - ($Offset - ($SideLength - 1)*2);
	$Y = $Rings;
} else {
	# Right row
	$X = $Rings;
	$Y = ($SideLength - 1)/2 - ($Offset - ($SideLength - 1) * 3);
}

$LongNotation = [math]::abs($X) + [math]::abs($Y);

# Short notation
if($Offset -lt $SideLength) {
	$X = 0; # Bottom row
} elseif($Offset -lt ($SideLength*2)-1) {
	$X = $SideLength; # Left row
} elseif($Offset -lt ($SideLength*3)-2) {
	$X = ($SideLength - 1)*2; # Top row
} else {
	$X = ($SideLength - 1) * 3;  # Right row
}

# Since we're always in the outer most row, we always get the distance of the number of rings.
$ShortNotation = $Rings + ($SideLength - 1)/2 - $Offset + $X;

Write-Host ("Part1 - LongN: {0}; ShortN: {1}" -f $LongNotation, $ShortNotation);


## Part two

