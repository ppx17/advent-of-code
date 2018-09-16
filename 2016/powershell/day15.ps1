Param(
    [string]$InputFile = '../input/input-day15.txt'
);

$Discs = Get-Content $InputFile | Foreach-Object { $_ -Match "Disc \#(?<offset>[0-9]) has (?<positions>[0-9]+) positions; at time=0, it is at position (?<startpos>[0-9]+)." | Out-Null; return $Matches; }

# $Delay = -1;
# do {
#     $Delay++;
#     $AllAligned = $True;
#     Foreach($Disc in $Discs) {
#         $CurrentPosition = ($Delay + $Disc['offset'] + $Disc['startpos']) % $Disc['positions'];
#         if($CurrentPosition -ne 0) {
#             $AllAligned = $False;
#             break;
#         }
#     }
# }while($AllAligned -eq $False);

Write-Output "Part 1: ${Delay}";

$Discs += @{"offset"=$Discs.Count + 1; "startpos"=0; "positions"=11};

$Delay = -1;
do {
    $Delay++;
    $AllAligned = $True;
    Foreach($Disc in $Discs) {
        $CurrentPosition = ($Delay + $Disc['offset'] + $Disc['startpos']) % $Disc['positions'];
        if($CurrentPosition -ne 0) {
            $AllAligned = $False;
            break;
        }
    }
}while($AllAligned -eq $False);

Write-Output "Part 2: ${Delay}";

# 3730820 too high