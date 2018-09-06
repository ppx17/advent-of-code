Param(
    [string]$InputFile = '../input/input-day11.txt'
);

Class Floors
{
    [string[][]]$Floors;

    Floors([string[]]$Content) 
    {
        $this.Floors = ($Content | ForEach-Object { ,([string[]]$_.Split(",")) });
    }

    [string[]]Generators([int]$Floor) 
    {
        return $this.Floors[$Floor] | Where-Object { $_ -Match "[A-Z]G" };
    }

    [string[]]MicroChips([int]$Floor) 
    {
        return $this.Floors[$Floor] | Where-Object { $_ -Match "[A-Z]M" };
    }

    [bool]HasGeneratorForChip($MicroChipName, $Floor)
    {
        return $this.Generators($Floor) -Contains $this.GeneratorNameForMicroChip($MicroChipName);
    }

    [string]Id([string]$ChipOrGenerator) 
    {
        return $ChipOrGenerator.Substring(0, 1);
    }

    [string]GeneratorNameForMicroChip([string]$MicroChipName) 
    {
        return ($this.Id($MicroChipName) + "G");
    }

    [string]MicroChipNameForGenerator([string]$GeneratorName) 
    {
        return ($this.Id($GeneratorName) + "M");
    }

    [bool]HasGenerators([int]$Floor) 
    {
        return $this.Generators($Floor).Count -gt 0;
    }

    [bool]HasMicroChips([int]$Floor) 
    {
        return $this.MicroChips($Floor).Count -gt 0;
    }
    
    [int[]]FloorNumbers() 
    {
        return (0..$this.TopFloor());
    }

    [int]TopFloor() 
    {
        return $this.Floors.Count -1;
    }

    [bool]HasItemOnFloor([string]$Item, [int]$Floor)
    {
        return $this.Floors[$Floor] -Contains $Item;
    }
    
    [void]RemoveItemFromFloor([string]$Item, [int]$Floor)
    {
        $this.Floors[$Floor] = $this.Floors[$Floor] | Where-Object { $_ -ne $Item }
    }

    [void]AddItemToFloor([string]$Item, [int]$Floor) {
        if($this.HasItemOnFloor($Item, $Floor)) {
            return;
        }
        $this.Floors[$Floor] += $Item;
    }
    
    [bool]IsValid() 
    {
        Foreach($Floor in $this.FloorNumbers())
        {
            if( -not $this.HasGenerators($Floor)) {
                continue;
            }
            if( -not $this.HasMicroChips($Floor)) {
                continue;
            }
            foreach($Chip in $this.MicroChips($Floor)) {
                if( -not $this.HasGeneratorForChip($Chip, $Floor)) {
                    return $False;
                }
            }
        }

        return $True;
    }
}

Class Elevator 
{
    [int]$StepsTake=0;
    [int]$CurrentFloor=0;
    [Floors]$Floors;

    Elevator([Floors]$Floors)
    {
        $this.Floors = $Floors;
    }

    [void]MoveUp([string]$Item1, [string]$Item2) 
    {
        if([String]::IsNullOrEmpty($Item1) -and [String]::IsNullOrEmpty($Item2)) {
            throw "Cannot move without items.";
        }
        $this.MoveItemUp($Item1);
        $this.MoveItemUp($Item2);
        $this.StepsTaken++;
        $this.CurrentFloor++;
    }

    [void]MoveDown([string]$Item1, [string]$Item2) 
    {
        if([String]::IsNullOrEmpty($Item1) -and [String]::IsNullOrEmpty($Item2)) {
            throw "Cannot move without items.";
        }
        $this.MoveItemDown($Item1);
        $this.MoveItemDown($Item2);
        $this.StepsTaken++;
        $this.CurrentFloor--;
    }

    [void]MoveItemUp([string]$Item) {
        $this.MoveItem($Item, 1);
    }

    [void]MoveItemDown([string]$Item) {
        $this.MoveItem($Item, -1);
    }

    [void]MoveItem([string]$Item, [int]$Direction) {
        if( -not $this.Floors.HasItemOnFloor($Item, $this.CurrentFloor)) {
            throw ("Attempts to elevate {0} from floor {1} but it isn't there!" -f $Item, $this.CurrentFloor);
        }
        $this.Floors.RemoveItemFromFloor($Item, $this.CurrentFloor);
        $this.Floors.AddItemToFloor($Item, $this.CurrentFloor + $Direction);
    }
}

$Floors = [Floors]::new((Get-Content $InputFile));
Write-Host $Floors.IsValid();

# Attempt to lift all generators up
for($Floor = 0; $Floor -le $Floors.TopFloor(); $Floor++) {
    $Generators = $Floors.Generators($Floor);
    if($Generators.Count -eq 0) {
        continue;
    }

}