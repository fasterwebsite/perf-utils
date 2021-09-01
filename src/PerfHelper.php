<?php
/**
 * @author Maciej Klepaczewski <matt@fasterwebsite.com>
 * @link https://fasterwebsite.com/
 * @copyright Copyright (c) 2021, Maciej Klepaczewski FasterWebsite.com
 */
declare(strict_types=1);

namespace fasterwebsite\perf\utils;


use Closure;

class PerfHelper {
    use Singleton;

    private array $timers;

    private array $activeTimers;

    /**
     * PerfHelper constructor.
     */
    public function __construct() {
        $this->timers = [];
        $this->activeTimers = [];
    }


    /**
     * @param string|array<string> $ids
     * @param Closure $callable
     * @return mixed
     */
    public function timeIt($ids, Closure $callable) {
        $ids = (array)$ids;

        $start = microtime(true);
        $result = $callable();
        $total = microtime(true) - $start;

        foreach($ids as $id) {
            $this->add($id, $total);
        }

        return $result;
    }

    public function add(string $id, float $time) : void {
        $this->timers[$id] = ($this->timers[$id] ?? 0.0) + $time;
    }

    /**
     * @return array
     */
    public function getTimers(): array {
        return $this->timers;
    }

    public function start(string $id) : void {
        if(isset($this->activeTimers[$id])) {
            die("Doing it wrong, already started the timer: '$id'");
        }
        $this->activeTimers[$id] = microtime(true);
    }

    public function end(string $id) : void {
        if(!isset($this->activeTimers[$id])) {
            die("Doing it wrong, timer not started: $id");
        }
        $this->add($id, microtime(true) - $this->activeTimers[$id]);
        unset($this->activeTimers[$id]);
    }

    public function startStop(string $id) : void {
        if(!isset($this->activeTimers[$id])) {
            $this->start($id);
        } else {
            $this->end($id);
        }
    }

}
