Param(
    [string]$InputFile = '../input/input-day9.txt'
);

$Compressed = (Get-Content $InputFile -Raw).Trim();

$Cursor = 0;
$UncompressedSize = 0;

while( ($Compressed.SubString($Cursor) -Match "\(([0-9]+)x([0-9]+)\)") ) {
    # If the match was not the first character at the cursor, we need to put all characters before it in uncompressed
    $Partial = $Compressed.SubString($Cursor);
    $Loc = $Partial.IndexOf($Matches[0]);
    # Add prefix to total length
    $UncompressedSize += $Loc;
    
    # Move the cursor to the match
    $Cursor += $Loc;

    # Expand the match
    $Size = [int]$Matches[1];
    $Times = [int]$Matches[2];
    $UncompressedSize += ($Size * $Times);

    # Move cursor along
    $Cursor += ($Matches[0].Length + $Size);
}

# If we have anything left, append it
$UncompressedSize += ($Compressed.Length - $Cursor);

Write-Output ("Part 1: {0}" -f $UncompressedSize);

##
## Part 2
##
class WeightCalculator
{        
    [UInt64]static GetLength([string]$Content) {
        $HasMarker = $Content -Match "\(([0-9]+)x([0-9]+)\)"
        if( -not $HasMarker) {
            return $Content.Length;
        }

        $Length = $MarkerPosition = $Content.IndexOf($Matches[0]);
        $Size = [UInt64]$Matches[1];
        $Times = [UInt64]$Matches[2];
        $Block = $Content.SubString($MarkerPosition + $Matches[0].Length, $Size);

        $BlockLength = [WeightCalculator]::GetLength($Block);

        $Length += $BlockLength * $Times;

        $Length += [WeightCalculator]::GetLength($Content.Substring($MarkerPosition + $Matches[0].Length + $Size));

        return $Length;
    }
}

$Compressed = (Get-Content $InputFile -Raw).Trim();
Write-Output ("Part 2: {0}" -f ([WeightCalculator]::GetLength($Compressed)));