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

    [bool[][]] static FromString([string]$FromString) {
        $Result = [System.Collections.ArrayList]@();
        $Lines = $FromString.Split("/");

        Foreach($Line in $Lines) {
            [void]$Result.Add( ([bool[]]($Line.ToCharArray() | ForEach-Object { $_ -eq "#" })) );
        }
        return $Result;
    }

    [string]static Character([bool]$Value) {
        if($Value) {
            return "#";
        }
        return ".";
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
        if($Rule -eq [MatrixHelper]::ToString($this.Matrix)) { return $true; }
        $RotatedMatrix = [MatrixHelper]::Clone($this.Matrix);
        # Rotate rule 3 times
        for($i=0;$i -lt 3; $i++) {
            [MatrixHelper]::Rotate($RotatedMatrix);
            if($Rule -eq [MatrixHelper]::ToString($RotatedMatrix)) { return $true; }
        }
        $FlippedMatrix = [MatrixHelper]::Clone($this.Matrix);
        [MatrixHelper]::Flip($FlippedMatrix);
        # Test all rotations
        for($i=0;$i -lt 4; $i++) {
            if($Rule -eq [MatrixHelper]::ToString($FlippedMatrix)) { return $true; }
            [MatrixHelper]::Rotate($FlippedMatrix);
        }

        return $false;
    }

    [int]Size() {
        return $this.Matrix.Count;
    }

    [Square[]]Squares() {
        $PartSize = 2 + ($this.Size() % 2);

        $result = [System.Collections.ArrayList]@();
        for($y = 0; $y -lt $this.Size(); $y += $PartSize) {
            for($x = 0; $x -lt $this.Size(); $x += $PartSize) {
               if($PartSize -eq 2) {
                    [void]$result.Add(
                        @(
                            @($this.Matrix[$y][$x], $this.Matrix[$y][$x+1]),
                            @($this.Matrix[$y+1][$x], $this.Matrix[$y+1][$x+1])
                        )
                    )
               } else {
                [void]$result.Add(
                    [Square]::new(@(
                        @($this.Matrix[$y][$x],   $this.Matrix[$y][$x+1],   $this.Matrix[$y][$x+2]),
                        @($this.Matrix[$y+1][$x], $this.Matrix[$y+1][$x+1], $this.Matrix[$y+1][$x+2]),
                        @($this.Matrix[$y+2][$x], $this.Matrix[$y+2][$x+1], $this.Matrix[$y+2][$x+2])
                    )));
               }
            }
        }

        return $result;
    }
}
