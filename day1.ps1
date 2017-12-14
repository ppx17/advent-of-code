Param(
	[string]$Str
);
$Chars = $Str.ToCharArray()
for($i,$y=0;$i -lt $Chars.Length;$i++) { 
	if($Chars[($i + 1) % $Chars.Length] -eq $Chars[$i]) {
		$y += ([convert]::ToInt32($Chars[$i], 10)); 
	}
}
return $y;

for($i,$y=0;$i -lt $Chars.Length;$i++) { 
	if($Chars[($i + ($Chars.Length / 2)) % $Chars.Length] -eq $Chars[$i]) {
		$y += ([convert]::ToInt32($Chars[$i], 10)); 
	}
}