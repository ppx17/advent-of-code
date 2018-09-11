[CmdLetBinding()]
Param(
    [string]$InputFile = '../input/input-day21.txt',
    [int]$Iterations = 2
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
        $MatrixString = (([Square]::new($Matrix)).ToString());
        Write-Host ("Pixels on: {0}" -f ([regex]::Matches($MatrixString, "#" )).count);
        Write-Host $MatrixString.Replace("/", "`n");
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
            Write-Verbose ("Matched Rule ${Rule} on matrix {0} without modification" -f [MatrixHelper]::ToString($this.Matrix));
            return $true; 
        }
        $RotatedMatrix = [MatrixHelper]::Clone($this.Matrix);
        # Rotate rule 3 times
        for($i=0;$i -lt 3; $i++) {
            [MatrixHelper]::Rotate($RotatedMatrix);
            if($Rule -eq [MatrixHelper]::ToString($RotatedMatrix)) 
            { 
                Write-Verbose ("Matched Rule ${Rule} on matrix {0} with {1} rotation(s)" -f [MatrixHelper]::ToString($this.Matrix),($i+1));
                return $true; 
            }
        }
        $FlippedMatrix = [MatrixHelper]::Clone($this.Matrix);
        [MatrixHelper]::Flip($FlippedMatrix);
        # Test all rotations
        for($i=0;$i -lt 4; $i++) {
            if($Rule -eq [MatrixHelper]::ToString($FlippedMatrix)) 
            { 
                Write-Verbose ("Matched Rule ${Rule} on matrix {0} with a flip and ${i} rotation(s)" -f [MatrixHelper]::ToString($this.Matrix));
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

# Apply Transformation Rules in double iteratons (2 -> 3 and 3 -> 4 a single TransformationRule)
for($i=1; $i -lt $Iterations; $i+=2) {

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

    
    # Create a new Matrix with 2x the size.
    $Matrix = [bool[][]]::new($MatrixSize*2, $MatrixSize*2);

    # Currently we only support transforming 2x2 to 4x4, so the end size is always 4.
    $TES = 4; # Transformation End Size

    # For every row of sub squares
    for($y=0;$y -lt $Squares.Count; $y++) {
        # For every column of subsquares
        for($x=0;$x -lt $Squares[0].Count; $X++) {
            # Find the rule that matches it
            foreach($TransformationRule in $TransformationRules) {
                # if rule matches...
                if($Squares[$y][$x].MatchesRule($TransformationRule.TwoByTwo)) {
                    # Grab the matching 4x4 matrix
                    $FourByFour = [MatrixHelper]::FromString($TransformationRule.FourByFour);
                    # For each of its rows
                    for($fy=0;$fy -lt $TES; $fy++) {
                        # and each of its columns
                        for($fx=0;$fx -lt $TES; $fx++) {
                            # Write to destination matrix
                            $Matrix[$y*$TES + $fy][$x*$TES + $fx] = $FourByFour[$fy][$fx];
                        }
                    }
                }
            }
        }
    }

    $CompleteSquare = [Square]::new($Matrix);

    [MatrixHelper]::WriteMatrixToHost($Matrix);
}

Write-Output "Ran ${i} iterations"
Write-Output ("Matrix is {0}x{1}" -f $CompleteSquare.Matrix.Count, $CompleteSquare.Matrix[0].Count);

# 114 too low
#8905 too high