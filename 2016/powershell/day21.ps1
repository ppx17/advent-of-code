Param(
    [string]$InputFile = '../input/input-day21.txt',
    [string]$StartForward = "abcdefgh",
    [string]$StartReverse = "fbgdceah"
);

$Rules = Get-Content $InputFile;

Class WordManipulator {
    [char[]]$Word;

    WordManipulator([string]$StartWord) {
        $this.Word = $StartWord.ToCharArray();
    }

    [void]SwapPositions([int]$X, [int]$Y) {
        $Tmp = $this.Word[$Y];
        $this.Word[$Y] = $this.Word[$X];
        $this.Word[$X] = $Tmp;
    }

    [void]ReverseSwapPositions([int]$X, [int]$Y) {
        $this.SwapPositions($X, $Y);
    }

    [void]SwapCharacters([char]$X, [char]$Y) {
        $IdxX = $this.Word.IndexOf($X);
        $IdxY = $this.Word.IndexOf($Y);
        $this.Word[$IdxX] = $Y;
        $this.Word[$IdxY] = $X;
    }

    [void]ReverseSwapCharacters([char]$X, [char]$Y) {
        $this.SwapCharacters($X, $Y);
    }

    [void]Rotate([int]$Steps, [string]$Direction) {
        if ($Direction -eq "left") {
            $Steps *= -1;
        }
        $this.Word = [Helper]::Rotate($this.Word, $Steps);
    }

    [void]ReverseRotate([int]$Steps, [string]$Direction) {
        $this.Rotate($Steps * -1, $Direction);
    }

    [void]RotateBasedOnLetter([char]$Letter) {
        $this.Word = [Helper]::RotateBasedOnLetter($this.Word, $Letter);
    }

    [void]ReverseRotateBasedOnLetter([char]$Letter) {
        for ($i = 0; $i -le $this.Word.Count; $i++) {
            $RotateBase = [Helper]::Rotate($this.Word, $i*-1);
            $RotateForward = [Helper]::RotateBasedOnLetter($RotateBase, $Letter);
            if ([Helper]::WordEquals($RotateForward, $this.Word)) {
                $this.Word = $RotateBase;
                break;
            }
        }
    }

    [void]InverseRange([int]$Start, [int]$End) {
        $Size = ($End - $Start) + 1
        $ReversePart = ($this.Word[$Start..$End])[$Size..0];
        $x = 0;
        for ($i = $Start; $i -le $End; $i++) {
            $this.Word[$i] = $ReversePart[$x]; $x++;
        }
    }

    [void]ReverseInverseRange([int]$Start, [int]$End) {
        $this.InverseRange($Start, $End);
    }

    [void]MovePosition([int]$SourceIndex, [int]$TargetIndex) {
        $Tmp = $this.Word[$SourceIndex];
        $List = ([System.Collections.ArrayList]$this.Word);
        $List.RemoveAt($SourceIndex);
        $List.Insert($TargetIndex, $Tmp);
        $this.Word = [char[]]$List;
    }

    [void]ReverseMovePosition([int]$SourceIndex, [int]$TargetIndex){
        $this.MovePosition($TargetIndex, $SourceIndex);
    }

    [string]ToString() {
        return ($this.Word -Join "");
    }
}

Class Helper {

    [char[]]static Rotate([char[]]$Word, [int]$Steps) {
        $RotatedWord = [char[]]::new($Word.Length);
        for ($i = 0; $i -lt $Word.Length; $i++) {
            $RotatedWord[($i + $Steps) % $Word.Length] = $Word[$i];
        }
        return $RotatedWord;
    }
    [char[]]static RotateBasedOnLetter([char[]]$Word, [char]$Letter) {
        $Pos = $Word.IndexOf($Letter);
        $Steps = 1 + $Pos;
        if ($Pos -ge 4) { $Steps++ }
        $Steps = $Steps % $Word.Count;
        return [Helper]::Rotate($Word, $Steps);
    }
    [bool]static WordEquals($Word1, $Word2) {
        if ($Word1.Count -ne $Word2.Count) { return $false }
        for ($i = 0; $i -lt $Word1.Count; $i++) {
            if ($Word1[$i] -ne $Word2[$i]) {
                return $false;
            }
        }
        return $true;
    }
}

$Manipulator = [WordManipulator]::new($StartForward);

foreach ($Rule in $Rules) {
    if ($Rule -match "^swap position ([0-9]+) with position ([0-9]+)$") {
        $Manipulator.SwapPositions($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^swap letter ([a-z]) with letter ([a-z])$") {
        $Manipulator.SwapCharacters($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^rotate (left|right) ([0-9]+) steps?$") {
        $Manipulator.Rotate($Matches[2], $Matches[1]);
    }
    elseif ($Rule -match "^rotate based on position of letter ([a-z])$") {
        $Manipulator.RotateBasedOnLetter($Matches[1]);
    }
    elseif ($Rule -match "^reverse positions ([0-9]+) through ([0-9]+)$") {
        $Manipulator.InverseRange($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^move position ([0-9]+) to position ([0-9]+)$") {
        $Manipulator.MovePosition($Matches[1], $Matches[2]);
    }
}

$Password = $Manipulator.ToString();

Write-Output "Part 1: ${Password}";

# Go backwards
[Array]::Reverse($Rules);

$Manipulator = [WordManipulator]::new($StartReverse);

foreach ($Rule in $Rules) {
    if ($Rule -match "^swap position ([0-9]+) with position ([0-9]+)$") {
        $Manipulator.ReverseSwapPositions($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^swap letter ([a-z]) with letter ([a-z])$") {
        $Manipulator.ReverseSwapCharacters($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^rotate (left|right) ([0-9]+) steps?$") {
        $Manipulator.ReverseRotate($Matches[2], $Matches[1]);
    }
    elseif ($Rule -match "^rotate based on position of letter ([a-z])$") {
        $Manipulator.ReverseRotateBasedOnLetter($Matches[1]);
    }
    elseif ($Rule -match "^reverse positions ([0-9]+) through ([0-9]+)$") {
        $Manipulator.ReverseInverseRange($Matches[1], $Matches[2]);
    }
    elseif ($Rule -match "^move position ([0-9]+) to position ([0-9]+)$") {
        $Manipulator.ReverseMovePosition($Matches[1], $Matches[2]);
    }
}

Write-Output ("Part 2: {0}" -f $Manipulator.ToString());
