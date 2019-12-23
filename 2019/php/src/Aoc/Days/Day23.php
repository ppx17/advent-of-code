<?php


namespace Ppx17\Aoc2019\Aoc\Days;


use Illuminate\Support\Collection;
use Ppx17\Aoc2019\Aoc\Days\Day23\IntCode;

class Day23 extends AbstractDay
{
    private const COMPUTER_COUNT = 50;

    private array $code;
    private Collection $computers;
    private Collection $queues;
    private array $messageBuffer;

    private ?array $natMessage = null;
    private int $lastNatY = 0;
    private array $idles = [];
    private int $part1 = 0;
    private int $part2 = 0;

    public function dayNumber(): int
    {
        return 23;
    }

    public function setUp(): void
    {
        $this->code = $this->getInputIntCode();

        $this->computers = Collection::times(self::COMPUTER_COUNT, function ($address) {
            $address--; //1 to zero indexed
            $computer = new IntCode($this->code);
            $computer->inputCallable = fn() => $this->input($address);
            $computer->outputCallable = fn($out) => $this->output($address, $out);
            return $computer;
        });
        $this->queues = Collection::times(self::COMPUTER_COUNT, function ($address) {
            $address--; // 1 to zero indexed.
            return collect([$address]);
        });
        $this->messageBuffer = [];
    }

    public function part1(): string
    {
        for ($i = 0; $i < 1_000_000; $i++) {
            $this->computers->each(fn(IntCode $c) => $c->tick());
            if (count($this->idles) === self::COMPUTER_COUNT) {
                $this->networkIdle();
            }
            if($this->part1 !== 0) {
                break;
            }
        }
        return (string)$this->part1;
    }

    public function part2(): string
    {
        for ($i = 0; $i < 1_000_000; $i++) {
            $this->computers->each(fn(IntCode $c) => $c->tick());
            if (count($this->idles) === self::COMPUTER_COUNT) {
                $this->networkIdle();
            }
            if($this->part2 !== 0) {
                break;
            }
        }
        return (string)$this->part2;
    }

    private function input($address)
    {
        $message = $this->queues->get($address)->shift();
        if (is_null($message)) {
            $this->idles[$address] = true;
            $message = -1;
        } else {
            unset($this->idles[$address]);
        }
        return $message;
    }

    private function output($address, $out)
    {
        if (!isset($this->messageBuffer[$address])) {
            $this->messageBuffer[$address] = ['target' => $out, 'x' => null, 'y' => null];
        } elseif (is_null($this->messageBuffer[$address]['x'])) {
            $this->messageBuffer[$address]['x'] = $out;
        } else {
            $this->messageBuffer[$address]['y'] = $out;
            $message = $this->messageBuffer[$address];
            if ($message['target'] === 255) {
                if($this->part1 === 0) {
                    $this->part1 = $message['y'];
                }
                $this->natMessage = $message;
            } else {
                $target = $this->queues->get($message['target']);
                $target->push($message['x']);
                $target->push($message['y']);
            }
            unset($this->messageBuffer[$address]);
        }
    }

    private function networkIdle()
    {
        $message = $this->natMessage;
        if ($message === null) {
            return;
        }
        if ($message['y'] === $this->lastNatY && $this->part2 === 0) {
            $this->part2 = $message['y'];
        }
        $this->lastNatY = $message['y'];
        $target = $this->queues->get(0);
        $target->push($message['x']);
        $target->push($message['y']);
        $this->natMessage = null;
    }
}
