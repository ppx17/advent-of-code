[CmdletBinding()]
Param(
    [string]$InputFile = '../input/input-day12.txt',
    [int]$ProgramId = 0
);

$Instructions = Get-Content $InputFile;

$Nodes = [System.Collections.ArrayList]@();

foreach($Instruction in $Instructions) {
    $Split = $Instruction -Split " <-> ";
    $References = $Split[1] -Split ", ";
    [void]$Nodes.Add($References);
}

Function DiscoverGroup($ProgramId) {
    $InGroup = [System.Collections.ArrayList]@();
    [void]$InGroup.Add([string]$ProgramId);

    Function AddChildren($ProgramId) {
        $Nodes[$ProgramId] | ForEach-Object { 
            if($InGroup.IndexOf($_) -eq -1) { 
                [void]$InGroup.Add($_);
                AddChildren($_);
            }
        }
    }

    AddChildren($ProgramId);
    return $InGroup;
}

$ZeroGroup = DiscoverGroup($ProgramId);
Write-Output ("Part 1: {0}" -f ($ZeroGroup | Measure-Object).Count);

$AllNodes = [System.Collections.ArrayList]((0..($Nodes.Count - 1)) | ForEach-Object { [string]$_; })

$ZeroGroup | Foreach-Object { $AllNodes.Remove($_); }

$GroupCount = 1;

do {
    $NextGroup = $AllNodes[0];
    $NextGroupNodes = DiscoverGroup($NextGroup);
    $NextGroupNodes | Foreach-Object { $AllNodes.Remove($_); }
    $GroupCount++;
}while($AllNodes.Count -gt 0);

Write-Output "Part 2: ${GroupCount}";