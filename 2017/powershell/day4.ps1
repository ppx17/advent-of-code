Param(
	[string]$InputFile = '../input/input-day4.txt'
);

Function Validate-DoubleWords {
	Param([string]$Phrase);
	$WordList = [System.Collections.ArrayList]@();
	return $(if($($Phrase -Split '\s+' | ForEach-Object { if($WordList.IndexOf($_) -gt -1) { return $True; break; } $null = $WordList.Add($_); })) { 0 }else{ 1 });
}

$sum = 0;
Get-Content $InputFile | ForEach-Object { $sum += (Validate-DoubleWords $_); }

Write-Host $sum;

Function Is-Anagram {
	Param([string]$Word1, $Word2);
		
	if($Word1.Length -ne $Word2.Length) {
		return $False;
	}
	$Count1 = [System.Collections.HashTable]@{};
	$Count2 = [System.Collections.HashTable]@{};
	$Word1.ToCharArray() | ForEach-Object { $Count1[$_]++; }
	$Word2.ToCharArray() | ForEach-Object { $Count2[$_]++; }
	$Result = $Count1.Keys | ForEach-Object{ if($Count1[$_] -ne $Count2[$_]) { return $True; break; } }
	return -Not $Result;
}

Function Validate-Anagrams {
	Param([string]$Phrase);
	$Words = $Phrase -Split '\s+';
	
	for($i=0;$i -lt $Words.Length; $i++) {
		for($y=$i+1;$y -lt $Words.Length; $y++) {
			if(Is-Anagram $Words[$i] $Words[$y]) {
				return 0;
			}
		}
	}
	return 1;
}

$sum = 0;
Get-Content 'input-day4.txt' | ForEach-Object {
	$PhraseResult = $(Validate-DoubleWords $_) + $(Validate-Anagrams $_);
	if($PhraseResult -eq 2) { $sum++ }
}
Write-Host $sum;