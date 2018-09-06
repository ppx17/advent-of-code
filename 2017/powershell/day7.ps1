[CmdletBinding()]
Param(
	[string]$InputFile = '../input/input-day7.txt'
);

Class Node {
	[String]$Parent;
	[String]$Name;
	[int]$Weight;
	[System.Collections.ArrayList]$ChildrenNames = @();
	[System.Collections.ArrayList]$Children = @();

	Node([string]$Name, [string[]]$ChildrenNames, $Weight) {
		$this.Name = $Name;
        $this.Weight = $Weight;
        if($ChildrenNames.Length -ne 0) {
		    $this.ChildrenNames.AddRange($ChildrenNames);
        }
	}

	[void]SetParent([string]$ParentName) {
		$this.Parent = $ParentName;
	}

    [int]TowerWeight() {
        $w = $this.Weight;
        $this.Children | ForEach-Object { $w += $_.TowerWeight(); }
        return $w;
    }

    [int[]] ChildrenTowerWeights() {
        return $this.Children | ForEach-Object { $_.TowerWeight() };
    }

    [bool]IsBalanced() {
        if($this.Children.Length -eq 0) {
            return $true;
        }else{
            $weights = $this.ChildrenTowerWeights();
            for($i=1;$i -lt $weights.Length;$i++) {
                if($weights[0] -ne $weights[$i]) {
                    return $false;
                }
            }
            return $true;
        }
    }
}

Class Tree {
	[System.Collections.HashTable]$Nodes = @{};

	[void] AddNode([string]$Name, [string[]]$ChildrenNames, $Weight) {

		$Node = [Node]::new($Name, $ChildrenNames, $Weight);

		$this.Nodes.Add($Name, $Node);

		# See if children of this node are already in the tree
        if($ChildrenNames.Length -ne 0) {
		    $ChildrenNames | ForEach-Object {
			    $Child = $this.Nodes.Item($_);
			    if($null -ne $Child) {
				    $Child.SetParent($Name);
			    }
		    };
        }

		# See if the parent of this node is already in the tree
		$this.Nodes.GetEnumerator() | ForEach-Object {
			if($_.Value.ChildrenNames.IndexOf($Name) -ne -1) {
				$Node.SetParent($_.Value.Name);
				#break;
			}
		};
	}

	[Node] FindRoot() {

		$RootCandidates = $this.Nodes.Values | Where-Object Parent -eq $null;

		if($RootCandidates.Length -eq 1) {
			return $RootCandidates[0];
		}else{
			return $null;
		}
	}

    [void] GiveNodesChildEntities() {
        $this.Nodes.GetEnumerator() | ForEach-Object {
            $Children = $($_.Value.ChildrenNames | ForEach-Object { $this.Nodes[$_] });
            if($Children.Length -gt 0) {
                $_.Value.Children.AddRange($Children);
            }
        }
    }

}

$Tree = [Tree]::new();


(Get-Content $InputFile) | Select-String -Pattern "^(?<Name>[a-z]+)\s+\((?<Weight>[0-9]+)\)(\s+\-\>\s+(?<Children>[a-z, ]+))?$" | ForEach-Object {
	$Name = $($_.Matches.Groups | Where-Object Name -eq "Name").Value;
	$Weight = $($_.Matches.Groups | Where-Object Name -eq "Weight").Value
	$RawChildren = $($_.Matches.Groups | Where-Object Name -eq "Children").Value

	$Tree.AddNode($Name, $($RawChildren.Split(", ") | Where-Object { $_ -ne ""} ), [convert]::ToInt32($Weight, 10));
}

$RootNode = $Tree.FindRoot();

$Tree.GiveNodesChildEntities();

Write-Host ("Root node name is {0}" -f $RootNode.Name);

Write-Host $RootNode.ChildrenTowerWeights();
Write-Host $RootNode.IsBalanced();

Function Test-Subtree {
    Param($Node);

    if($Node.IsBalanced()) {
        return $true;
        # all is balanced here.
    }

    # If one of my children is inbalanced, he must fix the problem
    $Node.Children | ForEach-Object {
        if(-not $_.IsBalanced()) {
            Test-Subtree -Node $_;
            return;
        }
    };

    # I'm the only one who can see the balance problem, find odd one out
    $Weights = $Node.ChildrenTowerWeights();

    $Mode = [convert]::ToInt32($($Weights | Group-Object | Sort-Object -Descending count | Select-Object -First 1).Name, 10);

    $ProblematicChild = $($Node.Children | Where-Object {$_.TowerWeight() -ne $Mode})[0];

    $Difference = $ProblematicChild.TowerWeight() - $Mode;

    Write-Host ("Child {0} was problematic with weight {1} in mode {2}. Diff {3}, new child weight {4}" -f $ProblematicChild.Name, $ProblematicChild.TowerWeight(), $Mode, $Difference, ($ProblematicChild.Weight-$Difference));
}

Test-Subtree $RootNode;
