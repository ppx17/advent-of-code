& powershell.exe -Command {
    # Dot sourcing classes doesn't reload them, so we're using a clean powershell instance for testing.
    . .\day21-library.ps1


    Describe "[MatrixHelper]" {
        context "With a 2x2 matrix" {
            $Matrix = [bool[][]]@(
                @( $false, $true ),
                @( $false, $false )
            );

            it "Should rotate correctly" {
                $Rotated = $Matrix.Clone();
                [MatrixHelper]::Rotate($Rotated);
                $Rotated[0][0] | Should -Be $true;
                $Rotated[0][1] | Should -Be $false;

                $Rotated[1][0] | Should -Be $false;
                $Rotated[1][1] | Should -Be $false;
            }
        }

        context "With a 3x3 matrix" {
            $Matrix =[bool[][]] @(
                @( $false, $true,  $false ),
                @( $false, $false, $true  ),
                @( $true,  $true,  $true  )
            );

            it "[MatrixHelper]::Clone() should work correctly" {
                $Clone = [MatrixHelper]::Clone($Matrix);
                $Matrix[0][0] | Should -Be $false;
                $Clone[0][0] | Should -Be $false;
                $Clone[0][0] = $true;

                $Matrix[0][0] | Should -Be $false;
                $Clone[0][0] | SHould -Be $true;
            }

            it "[MatrixHelper]::Rotate() should work correctly" {
                $Rotated = [MatrixHelper]::Clone($Matrix);
                [MatrixHelper]::Rotate($Rotated);
                $Rotated[0][0] | Should -Be $false;
                $Rotated[0][1] | Should -Be $true;
                $Rotated[0][2] | Should -Be $true;

                $Rotated[1][0] | Should -Be $true;
                $Rotated[1][1] | Should -Be $false;
                $Rotated[1][2] | Should -Be $true;

                $Rotated[2][0] | Should -Be $false;
                $Rotated[2][1] | Should -Be $false;
                $Rotated[2][2] | Should -Be $true;
            }

            it "[MatrixHelper]::Flip() should work correctly" {
                $Flipped = [MatrixHelper]::Clone($Matrix);
                [MatrixHelper]::Flip($Flipped);

                $Flipped[0][0] | Should -Be $false;
                $Flipped[0][1] | Should -Be $true;
                $Flipped[0][2] | Should -Be $false;

                $Flipped[1][0] | Should -Be $true;
                $Flipped[1][1] | Should -Be $false;
                $Flipped[1][2] | Should -Be $false;

                $Flipped[2][0] | Should -Be $true;
                $Flipped[2][1] | Should -Be $true;
                $Flipped[2][2] | Should -Be $true;
            }
        }

        context "[MatrixHelper]::FromString" {
            it "Should give a correct 4x4 matrix" {
                $Matrix = [MatrixHelper]::FromString("###./..#./.#../..##");

                $Matrix[0][0] | Should -Be $true;
                $Matrix[0][1] | Should -Be $true;
                $Matrix[0][2] | Should -Be $true;
                $Matrix[0][3] | Should -Be $false;

                $Matrix[1][0] | Should -Be $false;
                $Matrix[1][1] | Should -Be $false;
                $Matrix[1][2] | Should -Be $true;
                $Matrix[1][3] | Should -Be $false;

                $Matrix[2][0] | Should -Be $false;
                $Matrix[2][1] | Should -Be $true;
                $Matrix[2][2] | Should -Be $false;
                $Matrix[2][3] | Should -Be $false;

                $Matrix[3][0] | Should -Be $false;
                $Matrix[3][1] | Should -Be $false;
                $Matrix[3][2] | Should -Be $true;
                $Matrix[3][3] | Should -Be $true;
            }
        }
    }

    Describe "[Square]" {
        Context "With starting square" {
            $Square = [Square]::new(@(
                @( $false, $true,  $false ),
                @( $false, $false, $true  ),
                @( $true,  $true,  $true  )
            ));

            Context "ToString" {
                it "Returns the correct string representation" {
                    $Result = $Square.ToString();
                    $Result | Should -Be ".#./..#/###"
                }
            }

            Context "MatchesRule" {
                it "Should not match non-matching rule <Rule>" -TestCases @(
                    @{ Rule = '###/###/###' }
                    @{ Rule = '###/.../###' }
                ) {
                    Param($Rule);
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $false
                }

                it "Should match a non rotated rule" {
                    $Rule = ".#./..#/###";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a 1x rotated rule" {
                    $Rule = ".##/#.#/..#";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a 2x rotated rule" {
                    $Rule = "###/#../.#.";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a 3x rotated rule" {
                    $Rule = "#../#.#/##.";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a flipped, non rotated rule" {
                    $Rule = ".#./#../###";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a flipped, 1x rotated rule" {
                    $Rule = "##./#.#/#..";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a flipped, 2x rotated rule" {
                    $Rule = "###/..#/.#.";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }

                it "Should match a flipped, 3x rotated rule" {
                    $Rule = "..#/#.#/.##";
                    $Result = $Square.MatchesRule($Rule);
                    $Result | Should -Be $true
                }
            }
        }
    }
}
