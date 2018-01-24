Class Registry
{
	[System.Collections.Hashtable]$Registry;
	[int]$HighestEver;
	
	Registry()
	{
		$this.Registry = [system.collections.hashtable]@{};
		$this.HighestEver = 0;
	}
	
	[void] Init($registerName) {
		if( -not $this.Registry.Contains($registerName)) {
			[void]$this.Registry.Add($registerName, 0);
		}
	}
	
	[int] Get($registerName) {
		$this.Init($registerName);
		return $this.Registry[$registerName];
	}
	
	[void] Set($registerName, $value) {
		$this.Init($registerName);
		
		$this.SaveHighestEver($value);
		$this.Registry[$registerName] = $value;
	}
	
	[void] SaveHighestEver($value) {
		if($value -gt $this.HighestEver) {
			$this.HighestEver = $value;
		}
	}
	
	[void] Inc($registerName, $Value) {
		[void]$this.Set($registerName, $this.Get($registerName) + $Value);
	}
	
	[void] Dec($registerName, $Value) {
		[void]$this.Set($registerName, $this.Get($registerName) - $Value);
	}
	
	[int] MaxCurrent() {
		$Max = 0;
		$this.Registry.Values | % { if($Max -lt $_) { $Max = $_; } }
		return $Max;
	}
}

Function EvaluateCondition($Left, $Cond, $Right) {
	switch($Cond) {
		"!=" { return $Left -ne $Right }
		">" { return $Left -gt $Right }
		"<" { return $Left -lt $Right }
		">=" { return $Left -ge $Right }
		"<=" { return $Left -le $Right }
		"==" { return $Left -eq $Right }
	}
}
			


$Registry = [Registry]::new();

$Calls = Get-Content 'input-day8.txt';

Foreach($Call in $Calls) {
	[void]($Call -match '^(?<opReg>[a-z]+)\s(?<Op>inc|dec)\s(?<opVal>[-]?[0-9]+)\sif\s(?<condRegName>[a-z]+)\s(?<cond>[=!><]+)\s(?<condVal>[-]?[0-9]+)$');
	
	if( EvaluateCondition -Left $Registry.Get($Matches['condRegName']) -Cond $Matches['cond'] -Right ([convert]::ToInt32($Matches['condVal'], 10))) {
		switch($Matches['Op']) {
			"inc" { $Registry.Inc($Matches['opReg'], [convert]::ToInt32($Matches['opVal'])) }
			"dec" { $Registry.Dec($Matches['opReg'], [convert]::ToInt32($Matches['opVal'])) }
		}
	}
}

Write-Host $Registry.MaxCurrent();
Write-Host $Registry.HighestEver;

