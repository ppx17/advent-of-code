<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 16:16
 */

namespace Ppx17\Aoc2018\Days\Day13;


use Ppx17\Aoc2018\Days\Common\Vector;

class Cart
{
    private const CHOICES = ['left', 'straight', 'right'];
    public $location;
    public $direction;
    public $id;
    private $intersectionsSeen = 0;

    public function __construct(int $id, Vector $location, Vector $direction)
    {
        $this->id = $id;
        $this->location = $location;
        $this->direction = $direction;
    }

    public function move(): void
    {
        $this->location->add($this->direction);
    }

    public function collidesWith(Cart $cart): bool
    {
        return $this->id !== $cart->id && $this->location->equals($cart->location);
    }

    public function changeDirectionForTrack(string $track): void
    {
        if ($track === '+') {
            $this->intersection();
        } elseif ($track === '/') {
            $this->rightCorner();
        } elseif ($track === '\\') {
            $this->leftCorner();
        } elseif ($track === '-' || $track === '|') {
            // no turn needed
        } else {
            throw new \Exception('Unknown piece of track encountered "' . $track . '"');
        }
    }

    private function intersection(): void
    {
        $choice = self::CHOICES[$this->intersectionsSeen++ % 3];
        if ($choice === 'left') {
            $this->direction->turnLeft();
        } elseif ($choice === 'right') {
            $this->direction->turnRight();
        }
    }

    private function rightCorner(): void
    {
        if ($this->direction->isUp() || $this->direction->isDown()) {
            $this->direction->turnRight();
        } else {
            $this->direction->turnLeft();
        }
    }

    private function leftCorner(): void
    {
        if ($this->direction->isUp() || $this->direction->isDown()) {
            $this->direction->turnLeft();
        } else {
            $this->direction->turnRight();
        }
    }
}