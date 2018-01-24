$Stream = "197,97,204,108,1,29,5,71,0,50,2,255,248,78,254,63";

$Lengths = $Stream.Split(",") | % { [Convert]::ToInt32($_, 10) };

$List = (0..255);

$Skip = 0;

$Current = 0;

foreach($Length in $Lengths) {
	
	$Selection = 