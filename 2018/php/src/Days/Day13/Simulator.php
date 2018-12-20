<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 16:16
 */

namespace Ppx17\Aoc2018\Days\Day13;


use Ppx17\Aoc2018\Days\Common\Vector;

class Simulator
{
    private $carts;
    private $tracks;

    private $lastCartStanding;
    private $firstImpactLocation;

    public function __construct(array $carts, Tracks $tracks)
    {
        $this->carts = $carts;
        $this->tracks = $tracks;
        $this->firstImpactLocation = null;
    }

    public function run(): void
    {
        while ($this->runTick());

        // Re-key array so first is at index 0 again.
        $carts = array_values($this->carts);
        $this->lastCartStanding = $carts[0];
    }

    /**
     * @return mixed
     */
    public function getLastCartStanding(): Cart
    {
        return $this->lastCartStanding;
    }

    /**
     * @return mixed
     */
    public function getFirstImpactLocation(): Vector
    {
        return $this->firstImpactLocation;
    }

    private function sortCartsByPosition(): void
    {
        usort($this->carts, function ($a, $b) {
            if ($a->location->y !== $b->location->y) {
                // First sort on Y
                return $a->location->y - $b->location->y;
            }
            return $a->location->x - $b->location->x;
        });
    }

    private function hasMultipleCarts(): bool
    {
        return count($this->carts) > 1;
    }

    private function collideCarts(int $firstKey, int $secondKey): void
    {
        unset($this->carts[$firstKey], $this->carts[$secondKey]);
    }

    private function finishPartOne(Cart $cart): void
    {
        if ($this->firstImpactLocation === null) {
            $this->firstImpactLocation = $cart->location;
        }
    }

    private function runTick(): bool
    {
        $this->sortCartsByPosition();
        foreach ($this->carts as $key => $cart) {
            $cart->move();

            $this->checkForCollisions($cart, $key);

            if (!$this->hasMultipleCarts()) {
                return false;
            }

            $cart->changeDirectionForTrack($this->tracks->at($cart->location));
        }
        return true;
    }

    /**
     * @param $cart
     * @param $key
     */
    private function checkForCollisions(Cart $cart, int $key): void
    {
        foreach ($this->carts as $otherKey => $otherCart) {
            if ($cart->collidesWith($otherCart)) {
                $this->finishPartOne($cart);
                $this->collideCarts($key, $otherKey);
            }
        }
    }
}