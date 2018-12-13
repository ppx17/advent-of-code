<?php
$data = $data ?? file_get_contents("../input/input-" . basename(__FILE__, '.php') . ".txt");

class Vector
{
    public $x;
    public $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function add(Vector $vector): void
    {
        $this->x += $vector->x;
        $this->y += $vector->y;
    }

    public function equals(Vector $vector): bool
    {
        return $this->x === $vector->x && $this->y === $vector->y;
    }

    public function turnLeft(): void
    {
        if ($this->x === 0) {
            $this->x = $this->y;
            $this->y = 0;
        } else {
            $this->y = -$this->x;
            $this->x = 0;
        }
    }

    public function turnRight(): void
    {
        if ($this->x === 0) {
            $this->x = -$this->y;
            $this->y = 0;
        } else {
            $this->y = $this->x;
            $this->x = 0;
        }
    }

    public function isLeft(): bool
    {
        return $this->x === -1 && $this->y === 0;
    }

    public function isRight(): bool
    {
        return $this->x === 1 && $this->y === 0;
    }

    public function isUp(): bool
    {
        return $this->x === 0 && $this->y === -1;
    }

    public function isDown(): bool
    {
        return $this->x === 0 && $this->y === 1;
    }
}

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
            throw new Exception('Unknown piece of track encountered "' . $track . '"');
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

class Tracks
{
    private $tracks;

    public function __construct($tracks)
    {
        $this->tracks = $tracks;
    }

    public function at(Vector $location): string
    {
        return $this->tracks[$location->y][$location->x];
    }
}

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


$lines = explode("\n", $data);
$tracksArray = [];
$carts = [];
$cartCount = 0;
for ($y = 0; $y < count($lines); $y++) {
    $tracksArray[$y] = str_split($lines[$y], 1);
    for ($x = 0; $x < count($tracksArray[$y]); $x++) {
        $direction = null;
        switch ($tracksArray[$y][$x]) {
            case '>':
                $tracksArray[$y][$x] = '-';
                $direction = new Vector(1, 0);
                break;
            case '<':
                $tracksArray[$y][$x] = '-';
                $direction = new Vector(-1, 0);
                break;
            case '^':
                $tracksArray[$y][$x] = '|';
                $direction = new Vector(0, -1);
                break;
            case 'v':
                $tracksArray[$y][$x] = '|';
                $direction = new Vector(0, 1);
                break;
        }
        if ($direction !== null) {
            $carts[] = new Cart($cartCount++, new Vector($x, $y), $direction);
        }
    }
}

$tracks = new Tracks($tracksArray);
$simulator = new Simulator($carts, $tracks);

$simulator->run();

$location = $simulator->getFirstImpactLocation();
printf("Part 1: %s,%s\n", $location->x, $location->y);

$cart = $simulator->getLastCartStanding();
printf("Part 2: %s,%s\n", $cart->location->x, $cart->location->y);
