Param(
    [UInt64]$MaxRecurses
);


Class RecursiveTest {
    [UInt64]$MaxRecurses;
    [UInt64]$Current;

    RecursiveTest([UInt64]$MaxRecurses) {
        $this.MaxRecurses = $MaxRecurses;
        $this.Current = 0;
    }

    [void]Run() {
        $this.Current++;
        if($this.Current -lt $this.MaxRecurses) {
            $this.Run();
        }
    }
}

$Test = [RecursiveTest]::new($MaxRecurses);
$Test.Run();


Class ShortRecursiveTest {
    [void]static Run([UInt64]$Current, [UInt64]$MaxRecurses) {
        if($Current -lt $MaxRecurses) { [ShortRecursiveTest]::Run(($Current + 1), $MaxRecurses); }
    }
}
[ShortRecursiveTest]::Run(1, 100000);