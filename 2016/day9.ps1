Param(
    [string]$InputFile = 'input-day9.txt'
);

$Compressed = (Get-Content $InputFile -Raw).Trim();

$Cursor = 0;

$Uncompressed = [System.Text.StringBuilder]::new()

while( ($Compressed.SubString($Cursor) -Match "\(([0-9]+)x([0-9]+)\)") ) {
    # If the match was not the first character at the cursor, we need to put all characters before it in uncompressed
    $Partial = $Compressed.SubString($Cursor);

    $Loc = $Partial.IndexOf($Matches[0]);
    if($Loc -gt 0) {
        [void]$Uncompressed.Append($Partial.SubString(0, $Loc));
        
        # Move the cursor to the match
        $Cursor += $Loc;

        # Move partial ahead
        $Partial = $Compressed.SubString($Cursor);
    }

    # Expand the match
    $Size = [int]$Matches[1];
    $Times = [int]$Matches[2];
    $Block = $Partial.SubString($Matches[0].Length, $Size);

    [void]$Uncompressed.Append( ($Block * $Times) );

    # Move cursor along
    $Cursor += ($Matches[0].Length + $Size);
}

# If we have anything left, append it
[void]$Uncompressed.Append( $Compressed.SubString($Cursor) );

Write-Output ("Part 1: {0}" -f $Uncompressed.ToString().Length);

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