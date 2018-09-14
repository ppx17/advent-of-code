Param(
    [string]$Salt="ihaygndm",
    [int]$NumKeys = 64
);

Class HashGenerator {
    [int]$HashIterations = 1;
    [string]$Salt;
    [System.Collections.HashTable]$Cache;
    [System.Security.Cryptography.MD5CryptoServiceProvider]$Md5;
    [System.Text.UTF8Encoding]$Encoding;
    [int]$Hits=0;
    [int]$Misses=0;

    HashGenerator([string]$Salt, [int]$HashIterations = 1) {
        $this.Salt = $Salt;
        $this.HashIterations = $HashIterations;
        $this.Cache = [System.Collections.HashTable]::new();

        $this.Md5 = [System.Security.Cryptography.MD5CryptoServiceProvider]::new()
        $this.Encoding = [System.Text.UTF8Encoding]::new();
    }

    [string]DoHashOnce([string]$Payload) {
        return ([System.BitConverter]::ToString($this.Md5.ComputeHash($this.Encoding.GetBytes($Payload))).Replace("-","")).ToLower();
    }

    [string]Hash([int]$Index) {
        if($null -eq $this.Cache[$Index]) {
            $Hash = $this.DoHashOnce(("{0}{1}" -f $this.Salt, $Index));
            for($i=0;$i -lt $this.HashIterations - 1; $i++) {
                $Hash = $this.DoHashOnce($Hash);
            }
            $this.Misses++;
            $this.Cache[$Index] = $Hash;
        }else{
            $this.Hits++;
        }
        return $this.Cache[$Index];
    }
}

Class Qualifier {
    [bool]static HasTriple($Hash) {
        return $Hash -match "([a-z0-9])(?=\1\1)";
    }
    [string]static TripletCharacter($Hash) {
        $Hash -match "([a-z0-9])(?=\1\1)";
        return $Matches[1];
    }
    [bool]static HasQuintuple($Hash, $Character) {
        return $Hash -match "([${Character}])(?=\1\1\1\1)";
    }
}

$Part = 0;
foreach($HashIterations in @(1, 2017)) 
{
    $Part++;
    $HashGenerator = [HashGenerator]::new($Salt, $HashIterations);

    $KeysFound = 0;
    $Index = 0;
    while($KeysFound -lt $NumKeys) {
        $Hash = $HashGenerator.Hash($Index);
        if([Qualifier]::HasTriple($Hash)) {
            $Character = [Qualifier]::TripletCharacter($Hash);
            for($i=1; $i -le 1000; $i++) {
                $FollowupHash = $HashGenerator.Hash($Index + $i);
                if([Qualifier]::HasQuintuple($FollowupHash, $Character)) {
                    $KeysFound++;
                    break;
                }
            }
        }

        $Index++;
    }

    # Last $Index++ should be accounted for.
    Write-Output ("Part {0}: {1}" -f $Part,($Index - 1));

    Write-Output ("Hits: {0} Misses: {1}" -f $HashGenerator.Hits,$HashGenerator.Misses);
}
