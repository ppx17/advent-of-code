Param(
    [string]$InputFile = '../input/input-day4.txt'
);

$RoomList = Get-Content $InputFile;

$TotalSectorIds = 0;
$DecryptedRealRooms = [System.Collections.ArrayList]@();

foreach($Room in $RoomList) {

    if( -not ($Room -Match "^([a-z-]+)([0-9]+)\[([a-z]+)\]$")) {
        continue;
    }

    $Name = $Matches[1];
    $SectorID = $Matches[2];
    $Checksum = $Matches[3];

    $ExpectedChecksum = ($Name.ToCharArray() | Where-Object { $_ -Match "^[a-z]$" } | Group-Object | Sort-Object @{e={$_.Count}; a=0}, Name | Select-Object -First 5 | Select-Object -ExpandProperty Name) -Join ""

    if($ExpectedChecksum -ne $Checksum) {
        continue;
    }
    
    $TotalSectorIds += $SectorID;

    $DecryptedName = "";
    Foreach($Char in $Name.ToCharArray()) {
        if($Char -eq '-') {
            $DecryptedName = $DecryptedName + " ";
            continue;
        }
        $AsciiCode = [byte][int]$Char;
        $AsciiCode -= 97;
        $AsciiCode += $SectorId;
        $AsciiCode = $AsciiCode % 26;
        $AsciiCode += 97;
        $Char = ([char]$AsciiCode);
        $DecryptedName = $DecryptedName +  $Char;
    }
    [void]$DecryptedRealRooms.Add("${DecryptedName} (${SectorID})");
}

Write-Output "Part 1: ${TotalSectorIds}";

$NorthPoleRoom = $DecryptedRealRooms | Where-Object {$_ -Like "*northpole*"} | Select-Object -First 1;
Write-Output "Part 2: {$NorthPoleRoom}"