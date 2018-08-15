Param(
	$Stream,
	$InputFile = 'input-day9.txt'
);
if($null -eq $Stream) {
	$Stream = Get-Content $InputFile;
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

Write-Output "Part 1: ${GroupLevelCount}";

# Part two

$TotalLength = 0;
([regex]"<[^>]*>").Matches($EscapedStream) | ForEach-Object { $TotalLength += $_.Length - 2; }

Write-Output "Part 2: ${TotalLength}";
