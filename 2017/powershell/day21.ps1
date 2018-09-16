[CmdLetBinding()]
Param(
    [string]$InputFile = '../input/input-day21.txt',
    [int]$Iterations = 5
);

Class MatrixHelper {
    # Algorithm from https://www.geeksforgeeks.org/inplace-rotate-square-matrix-by-90-degrees/
    [void]static Rotate([bool[][]]$Matrix) {
        $N = $Matrix[0].Length;

        # Consider all squares one by one
        for ([int]$x = 0; $x -lt $N / 2; $x++)
        {
            # Consider elements in group of 4 in current square
            for ([int]$y = $x; $y -lt $N - $x - 1; $y++)
            {
                # store current cell in temp variable
                [int]$temp = $Matrix[$x][$y] ;

                # move values from right to top
                $Matrix[$x][$y]  = $Matrix[$y][$N - 1 - $x];

                # move values from bottom to right
                $Matrix[$y][$N - 1 - $x] = $Matrix[$N - 1 - $x][$N - 1 - $y];

                # move values from left to bottom
                $Matrix[$N - 1 - $x][$N - 1 - $y] = $Matrix[$N - 1 - $y][$x];

                # assign temp to left
                $Matrix[$N - 1 - $y][$x] = $temp;
            }
        }
    }

    [void]static Flip([bool[][]]$Matrix) {
        for ([int]$y = 0; $y -lt $Matrix.Length; $y++)
        {
            [Array]::Reverse($Matrix[$y]);
        }
    }

    [bool[][]]static Clone([bool[][]]$Matrix) {
        $Result = New-Object bool[][] $Matrix.Count;
        for ([int]$y = 0; $y -lt $Matrix.Length; $y++)
        {
            $Result[$y] = $Matrix[$y].Clone();
        }
        return $Result;
    }

    [string]static ToString([bool[][]]$Matrix) {
        $Size = $Matrix[0].Length;
        $Result = [System.Text.StringBuilder]::new();
        for($y=0;$y -lt $Size; $y++) {
            for($x=0;$x -lt $Size; $x++) {
                $Result.Append([MatrixHelper]::Character($Matrix[$y][$x]));
            }
            if($y -lt $Size -1) { $Result.Append("/"); }
        }
        return $Result.ToString();
    }

    [void]static WriteMatrixToHost([bool[][]]$Matrix) {
        [MatrixHelper]::WriteMatrixToHost($Matrix, $True);
    }

    [void]static WriteMatrixToHost([bool[][]]$Matrix, [bool]$PrintMatrix) {
        $MatrixString = (([Square]::new($Matrix)).ToString());
        Write-Host ("Pixels on: {0}; Matrix Size {1}x{2}" -f ([regex]::Matches($MatrixString, "#" )).count,
            $Matrix.Count, $Matrix[0].Count);
        if($PrintMatrix) {
            Write-Host $MatrixString.Replace("/", "`n");
        }
    }

    [bool[][]] static FromString([string]$FromString) {
        $Result = [System.Collections.ArrayList]@();
        $Lines = $FromString.Split("/");

        Foreach($Line in $Lines) {
            [void]$Result.Add( ([bool[]]($Line.ToCharArray() | ForEach-Object { $_ -eq "#" })) );
        }
        return $Result;
    }

    [Square] static SquareFromString([string]$FromString) {
        return [Square]::new([MatrixHelper]::FromString($FromString));
    }

    [string]static Character([bool]$Value) {
        return $(if($Value) {"#"}else{"."});
    }
}

Class Square {
    [bool[][]]$Matrix;

    Square([bool[][]]$Matrix) {
        $this.Matrix = $Matrix;
    }

    [string]ToString() {
        return [MatrixHelper]::ToString($this.Matrix);
    }

    [bool]MatchesRule([string]$Rule) {
        if($Rule -eq [MatrixHelper]::ToString($this.Matrix)) 
        { 
            return $true; 
        }
        $RotatedMatrix = [MatrixHelper]::Clone($this.Matrix);
        # Rotate rule 3 times
        for($i=0;$i -lt 3; $i++) {
            [MatrixHelper]::Rotate($RotatedMatrix);
            if($Rule -eq [MatrixHelper]::ToString($RotatedMatrix)) 
            { 
                return $true; 
            }
        }
        $FlippedMatrix = [MatrixHelper]::Clone($this.Matrix);
        [MatrixHelper]::Flip($FlippedMatrix);
        # Test all rotations
        for($i=0;$i -lt 4; $i++) {
            if($Rule -eq [MatrixHelper]::ToString($FlippedMatrix)) 
            { 
                return $true; 
            }
            [MatrixHelper]::Rotate($FlippedMatrix);
        }

        return $false;
    }

    [int]Size() {
        return $this.Matrix.Count;
    }
}

Class TransformationRule {
    [string]$TwoByTwo;
    [string]$ThreeByThree;
    [string]$FourByFour;
}

Class TransformationRuleFinder {
    [System.Collections.ArrayList]$TransformationRules;
    [System.Collections.Hashtable]$Index;

    TransformationRuleFinder([System.Collections.ArrayList]$TransformationRules) {
        $this.TransformationRules = $TransformationRules;
        $this.Index = [System.Collections.Hashtable]::new();
    }

    [TransformationRule]RuleForSquare([Square]$Square) {
        $Key = $Square.ToString();
        if($null -eq $this.Index[$Key]) {
            foreach($TransformationRule in $this.TransformationRules) {
                # if rule matches...
                if($Square.MatchesRule($TransformationRule.TwoByTwo)) {
                    $this.Index[$Key] = $TransformationRule;
                    break;
                }
            }
        }
        return $this.Index[$Key];
    }

}

$StartLayout = ".#./..#/###";

$Rules = ([string[][]](Get-Content $InputFile | ForEach-Object { ,@($_ -Split " => "); }));

# Make sure we start with a 4x4 square, that matches 2x2, effectively the first transformation
$CompleteSquare = $null;
$StartLayoutSquare = [MatrixHelper]::SquareFromString($StartLayout);
foreach($Rule in $Rules) {
    if($StartLayoutSquare.MatchesRule($Rule[0])) {
        $CompleteSquare = [MatrixHelper]::SquareFromString($Rule[1]);
        break;
    }
}

[MatrixHelper]::WriteMatrixToHost($CompleteSquare.Matrix);

$TransformationRules = [System.Collections.ArrayList]::new();

# Make transformation rules directly from 2x2 to 4x4
foreach($Rule in $Rules) {
    if($Rule[0].Length -eq 5) {
        $TransformationRule = [TransformationRule]::new();
        $TransformationRule.TwoByTwo = $Rule[0];
        $TransformationRule.ThreeByThree = $Rule[1];

        $ThreeByThreeSquare = [MatrixHelper]::SquareFromString($TransformationRule.ThreeByThree);
        foreach($MatchRule in $Rules) {
            if($ThreeByThreeSquare.MatchesRule($MatchRule[0])) {
                $TransformationRule.FourByFour = $MatchRule[1];
                break;
            }
        }

        [void]$TransformationRules.Add($TransformationRule);
    }
}

$RuleFinder = [TransformationRuleFinder]::new($TransformationRules);

# Apply Transformation Rules in double iteratons (2 -> 3 and 3 -> 4 a single TransformationRule)
for($i=1; $i -lt $Iterations; $i++) {
    Write-Host -NoNewline ("Iteration: {0}" -f ($i + 1));
    $StartTime = (Get-Date);
    $MatrixSize = $CompleteSquare.Matrix[0].Count;

    $Squares = [Square[][]]::new($MatrixSize / 2, $MatrixSize / 2);

    # Break up the CompleteSquare in 2x2 squares
    for($y=0; $y -lt $MatrixSize; $y+=2) {
        for($x=0; $x -lt $MatrixSize; $x+=2) {
            $MiniMatrix = [bool[][]]@(
                @($CompleteSquare.Matrix[$y][$x], $CompleteSquare.Matrix[$y][$x+1]),
                @($CompleteSquare.Matrix[$y+1][$x], $CompleteSquare.Matrix[$y+1][$x+1])
            );

            $NewSquare = [Square]::new($MiniMatrix);
            $Squares[$y/2][$x/2] = $NewSquare;
        }
    }

    if( ($MatrixSize * 1.5) % 2 -eq 0 -or $i -eq ($Iterations - 1)) {
        # Just a single iteration will give an evenly divisable matrix, only do 1 iteration.
        $TES = 3;
        
        # Create a new Matrix with 2x the size.
        $Matrix = [bool[][]]::new($MatrixSize*1.5, $MatrixSize*1.5);
    }else{
        $TES = 4;
        
        # Create a new Matrix with 2x the size.
        $Matrix = [bool[][]]::new($MatrixSize*2, $MatrixSize*2);
        $i++;
    }

    # For every row of sub squares
    for($y=0;$y -lt $Squares.Count; $y++) {
        # For every column of subsquares
        for($x=0;$x -lt $Squares[0].Count; $X++) {
            # Find the rule that matches it
            $TransformationRule = $RuleFinder.RuleForSquare($Squares[$y][$x]);
            
            if($TES -eq 3) {
                # Grab the matching 3x3 matrix
                $ResultMatrix = [MatrixHelper]::FromString($TransformationRule.ThreeByThree);
            }elseif($TES -eq 4) {
                # Grab the matching 4x4 matrix
                $ResultMatrix = [MatrixHelper]::FromString($TransformationRule.FourByFour);
            }
            
            # For each of its rows
            for($fy=0;$fy -lt $TES; $fy++) {
                # and each of its columns
                for($fx=0;$fx -lt $TES; $fx++) {
                    # Write to destination matrix
                    $Matrix[$y*$TES + $fy][$x*$TES + $fx] = $ResultMatrix[$fy][$fx];
                }
            }
        }
    }

    $CompleteSquare = [Square]::new($Matrix);

    Write-Host (" - {0:N0} sec" -f ((Get-Date) - $StartTime).TotalSeconds);
}

Write-Output "Ran ${i} iterations";
[MatrixHelper]::WriteMatrixToHost($Matrix, ($i -le 5));
