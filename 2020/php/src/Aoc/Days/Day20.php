<?php

namespace Ppx17\Aoc2020\Aoc\Days;

use Illuminate\Support\Collection;
use PHPUnit\Util\Exception;

class Day20 extends AbstractDay
{
    private Collection $tiles;
    private Collection $links;

    public function dayNumber(): int
    {
        return 20;
    }

    public function setUp(): void
    {
        $this->tiles = collect(explode("\n\n", $this->getInputTrimmed()))
            ->map(fn($x) => explode("\n", $x))
            ->mapWithkeys(fn($x) => [substr($x[0], 5, 4) => array_slice($x, 1)]);

        $sidesPerSquare = $this->tiles->map(fn($x) => $this->sides($x));

        $links = [];

        foreach ($sidesPerSquare as $id => $sides) {
            foreach ($sidesPerSquare as $otherId => $otherSides) {
                if ($id === $otherId) continue;

                foreach ($sides as $key => $side) {
                    if (in_array($side, $otherSides)) {
                        $links[$id] ??= [];
                        $links[$id][] = [$otherId, $key, array_search($side, $otherSides)];
                    }
                }
            }
        }

        $this->links = collect($links);
    }

    public function part1(): string
    {
        return array_product($this->links
            ->filter(fn($l) => count($l) === 4)
            ->keys()
            ->toArray());
    }

    public function part2(): string
    {
        $grid = $this->orientTilesIntoGrid();

        $image = $this->renderImage($grid);
        $nessies = $this->findNessiesWithTransformations($image);
        $waves = substr_count(implode('', $image), '#');

        return $waves - ($nessies * 15); // nessy consists of 15 pixels
    }

    private function orientTilesIntoGrid(): array
    {
        $sidelength = sqrt($this->tiles->count());
        $corners = $this->links->filter(fn($l) => count($l) === 4);
        $firstCornerId = $corners->keys()->first();
        $firstCornerLinks = collect($corners[$firstCornerId])
            ->filter(fn($x) => strpos($x[1], 'flipped') === false);
        $neighboringSides = $firstCornerLinks->map(fn($x) => $x[1]);

        // orient $cornerTile to be the top left, having neighbors at its bottom and right
        $cornerTile = $this->tiles[$firstCornerId];
        if (in_array('left', $neighboringSides->toArray())) {
            $cornerTile = $this->flipHorizontal($cornerTile);
        }
        if (in_array('top', $neighboringSides->toArray())) {
            $cornerTile = $this->flipVertical($cornerTile);
        }
        $idGrid = [[$firstCornerId]];
        $grid = [[$cornerTile]];

        for ($y = 0; $y < $sidelength; $y++) {
            if ($y > 0) {
                $idToTop = $idGrid[$y - 1][0];
                $candidates = $this->links[$idToTop];

                $candidateIds = array_unique(array_map(fn($x) => $x[0], $candidates));

                $onMyTopSide = $this->bottomSide($grid[$y - 1][0]);

                [$id, $tile] = $this->selectCandidateWithTopSide($candidateIds, $onMyTopSide);
                $idGrid[$y][0] = $id;
                $grid[$y][0] = $tile;
            }

            for ($x = 1; $x < $sidelength; $x++) {

                $idToLeft = $idGrid[$y][$x - 1];
                $candidates = $this->links[$idToLeft];

                $candidateIds = array_unique(array_map(fn($x) => $x[0], $candidates));

                $onMyLeftSide = $this->rightSide($grid[$y][$x - 1]);

                $expectedTopSide = null;

                if ($y > 0) {
                    $expectedTopSide = $this->bottomSide($grid[$y - 1][$x]);
                }

                [$id, $tile] = $this->selectCandidateWithLeftSide($candidateIds, $onMyLeftSide, $expectedTopSide);
                $idGrid[$y][$x] = $id;
                $grid[$y][$x] = $tile;

            }
        }
        return $grid;
    }

    private function selectCandidateWithLeftSide(array $candidateIds, string $expectedLeftSide, ?string $expectedTopSide = null): array
    {
        $tile = null;

        foreach ($candidateIds as $id) {
            $tile = $this->tiles[$id];
            if (!in_array($expectedLeftSide, $this->sides($tile))) {
                continue;
            }

            for ($i = 0; $i < 4; $i++) {
                if ($this->leftSide($tile) == $expectedLeftSide) {
                    if (is_null($expectedTopSide) || $expectedTopSide == $this->topSide($tile)) {
                        return [$id, $tile];
                    }
                }
                if (strrev($this->leftSide($tile)) == $expectedLeftSide) {
                    if (is_null($expectedTopSide) || $expectedTopSide == $this->bottomSide($tile)) {
                        return [$id, $this->flipVertical($tile)];
                    }
                }

                $tile = $this->rotateRight($tile);
            }
        }

        throw new Exception('Could not get tile to fit');
    }

    private function selectCandidateWithTopSide(array $candidateIds, string $expectedTopSide): array
    {
        $tile = null;
        foreach ($candidateIds as $id) {
            $tile = $this->tiles[$id];
            if (!in_array($expectedTopSide, $this->sides($tile))) {
                continue;
            }

            for ($i = 0; $i < 4; $i++) {
                if ($this->topSide($tile) == $expectedTopSide) {
                    return [$id, $tile];
                }
                if (strrev($this->topSide($tile)) == $expectedTopSide) {
                    return [$id, $this->flipHorizontal($tile)];
                }

                $tile = $this->rotateRight($tile);
            }
        }

        throw new Exception('Could not get tile to fit');
    }

    private function findNessiesWithTransformations(array $image): int
    {
        for ($flip = 0; $flip < 2; $flip++) {
            for ($rot = 0; $rot < 4; $rot++) {
                $nessies = $this->findNessies($image);
                if ($nessies > 0) return $nessies;
                $image = $this->rotateRight($image);
            }
            $image = $this->flipHorizontal($image);
        }

        throw new Exception('Could not find any Nessies');
    }

    private function findNessies(array $image): int
    {
        $nessies = 0;
        $height = count($image);
        $width = strlen($image[0]);
        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                $nessies += (int)$this->isThatNessyAt($image, $y, $x);
            }
        }

        return $nessies;
    }

    private function isThatNessyAt(array $image, int $y, int $x): bool
    {
        $nessy = [
            [18],
            [0, 5, 6, 11, 12, 17, 18, 19],
            [1, 4, 7, 10, 13, 16]
        ];

        foreach ($nessy as $ny => $cols) {
            foreach ($cols as $nx) {
                if (($image[$y + $ny][$x + $nx] ?? '.') !== '#') {
                    return false;
                }
            }
        }
        return true;
    }

    private function sides(array $square): array
    {
        $sides = [
            'top' => $square[0],
            'bottom' => $square[9],
            'left' => $this->leftSide($square),
            'right' => $this->rightSide($square)
        ];

        $res = [];
        foreach ($sides as $place => $s) {
            $res[$place] = $s;
            $res[$place . '_flipped'] = strrev($s);
        }
        return $res;
    }

    private function rotateRight(array $square): array
    {
        $res = [];
        for ($y = 0; $y < count($square); $y++) {
            $res[$y] = strrev($this->column($square, $y));
        }
        return $res;
    }

    private function flipVertical(array $square): array
    {
        $res = [];
        $x = 0;
        for ($y = count($square) - 1; $y >= 0; $y--) {
            $res[$x++] = $square[$y];
        }
        return $res;
    }

    private function flipHorizontal(array $square): array
    {
        return array_map(fn($x) => strrev($x), $square);
    }

    private function column(array $square, int $column): string
    {
        $res = '';
        foreach ($square as $row) {
            $res .= $row[$column];
        }
        return $res;
    }

    private function rightSide(array $square): string
    {
        return $this->column($square, 9);
    }

    private function leftSide(array $square): string
    {
        return $this->column($square, 0);
    }

    private function topSide(array $square): string
    {
        return $square[0];
    }

    private function bottomSide(array $square): string
    {
        return $square[9];
    }

    private function renderImage(array $grid): array
    {

        $trimmedGrid = [];
        for ($y = 0; $y < count($grid); $y++) {
            $trimmedGrid[$y] = [];
            for ($x = 0; $x < count($grid[0]); $x++) {

                $trimmedGrid[$y][$x] = array_map(
                    fn($x) => substr($x, 1, 8),
                    array_slice($grid[$y][$x], 1, 8)
                );
            }
        }

        $image = [];
        foreach ($trimmedGrid as $y => $row) {
            $lines = [];
            foreach ($row as $x => $tile) {
                foreach ($tile as $lineNr => $line) {
                    $lines[$lineNr] ??= '';
                    $lines[$lineNr] .= $line;
                }
            }
            $image = array_merge($image, $lines);
        }

        return $image;
    }
}
