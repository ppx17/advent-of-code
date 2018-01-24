Param(
	$Stream
);
if($Stream -eq $Null) {
	$Stream = GC 'input-day9.txt';
}
$EscapedStream = ($Stream -replace "!.","");

$GarbagelessStream = ($EscapedStream -replace "<[^>]*>","");

$GroupLevelCount = 0;
$CurrentLevel = 0;

foreach($Char in $GarbagelessStream.ToCharArray()) {
	if($Char -eq '{') {
		$CurrentLevel++;
		$GroupLevelCount += $CurrentLevel;
	}elseif($Char -eq '}') {
		$CurrentLevel--;
	}
}

Write-host $GroupLevelCount;

# Part two

$TotalLength = 0;
([regex]"<[^>]*>").Matches($EscapedStream) | % { $TotalLength += $_.Length - 2; }

Write-Host $TotalLength;