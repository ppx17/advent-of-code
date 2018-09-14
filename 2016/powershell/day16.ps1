Param(
    [string]$InputString = '11011110011011101'
);


Class DragonCurve {
    [System.Collections.BitArray]$Bits;

    DragonCurve([String]$BitString) {
        $this.Bits = [System.Collections.BitArray]::new($BitString.Length);
        $i = 0;
        foreach($Str in $BitString.ToCharArray()) {
            $this.Bits[$i++] = $(if($Str -eq '1') { 1 }else{ 0 });
        }
    }

    [void]RunStep() {
        $Result = [System.Collections.BitArray]::new(($this.Bits.Length * 2) + 1);
        
        # First A
        for($i=0;$i-lt$this.Bits.Length; $i++) {
            $Result[$i] = $this.Bits[$i];
        }

        # Then a 0
        $i++;
        
        # Then A reversed and XOR'ed
        for($x=$this.Bits.Length - 1; $x -ge 0; $x--) {
            $Result[$i] = $this.Bits[$x] -xor 1;
            $i++;
        }

        $this.Bits = $Result;
    }

    [void]RunUntilLength([int]$Length) {
        while($this.Bits.Count -lt $Length) {
            $this.RunStep();
        }
    }

    [System.Collections.BitArray]Chop([int]$Length) {
        $Result = [System.Collections.BitArray]::new($Length);
        for($i=0;$i -lt $Length; $i++) {
            $Result[$i] = $this.Bits[$i];
        }
        return $Result;
    }

    [String]ToString() {
        return [Helper]::BitArrayToString($this.Bits);
    }
}

Class Checksum {
    
    [string]static Calculate([System.Collections.BitArray]$Data) {
        do {
            $Hash = [System.Collections.BitArray]::new($Data.Length / 2);
            for($i=0; $i -lt $Data.Length; $i+=2) {
                $Hash[$i / 2] = ($Data[$i] -eq $Data[$i+1]);
            }
            $Data = $Hash;
        }while($Hash.Length % 2 -eq 0);

        return [Helper]::BitArrayToString($Hash);
    }
}

Class Helper {
    [string]Static BitArrayToString([System.Collections.BitArray]$Array) {
        return ($Array | Foreach-Object { $(if($_) { "1" }else{"0"})}) -Join "";
    }
}

#$Curve = [DragonCurve]::new($InputString);

$Part = 1;
foreach($Length in @(272, 35651584)) {
    $Curve = [DragonCurve]::new($InputString);
    $Curve.RunUntilLength($Length);

    Write-Output ("Part {0}: {1}" -f $Part++,([Checksum]::Calculate($Curve.Chop($Length))));
}

