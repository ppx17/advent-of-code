Param(
    [string]$InputFile = '../input/input-day7.txt'
);

$Addresses = Get-Content $InputFile;

$TlsIps = 0;

foreach($Address in $Addresses) {
    $Chars = $Address.ToCharArray();

    $InHypernet = $false;
    $AbbaInHypernet = $false;
    $SupportsTls = $false;
    for($i=0;($i -lt ($Chars.Count - 3) -and $AbbaInHypernet -eq $false); $i++) {
        if($Chars[$i] -eq '[') {
            $InHypernet = $true;
            continue;
        }
        if($Chars[$i] -eq ']') {
            $InHypernet = $false;
            continue;
        }

        if($Chars[$i] -ne $Chars[$i+1] -and
            $Chars[$i+1] -eq $Chars[$i+2] -and
            $Chars[$i] -eq $Chars[$i+3]) {
            if($InHypernet) {
                $SupportsTls = $false;
                $AbbaInHypernet = $true; # Set this flag to break out early
            }else{
                $SupportsTls = $true;
            }
        }
    }

    if($SupportsTls) {
        $TlsIps++;
    }
}

Write-Output "Part 1: ${TlsIps}";

foreach($Address in $Addresses) {
    $Chars = $Address.ToCharArray();
    $WantedBabs = [System.Collections.ArrayList]@();
    $FoundBabs = [System.Collections.ArrayList]@();
    $InHypernet = $false;
    for($i=0;$i -lt ($Chars.Count - 2); $i++) {
        if($Chars[$i] -eq '[') {
            $InHypernet = $true;
            continue;
        }
        if($Chars[$i] -eq ']') {
            $InHypernet = $false;
            continue;
        }

        if($Chars[$i] -ne $Chars[$i+1] -and
            $Chars[$i] -eq $Chars[$i+2]) {
            if($InHypernet) {
                [void]$FoundBabs.Add($Chars[$i]+$Chars[$i+1]+$Chars[$i+2]);
            }else{
                [void]$WantedBabs.Add($Chars[$i+1]+$Chars[$i]+$Chars[$i+1]);
            }
        }
    }

    $IsSsl = ($WantedBabs | Where-Object { $FoundBabs -Contains $_}).Count -ge 1;

    if($IsSsl) {
        $SslIps++;
    }
}

Write-Output "Part 2: ${SslIps}";