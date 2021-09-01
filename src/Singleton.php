<?php
/**
 * @author    Maciej Klepaczewski <matt@fasterwebsite.com>
 * @link      https://fasterwebsite.com/
 * @copyright Copyright (c) 2020, Maciej Klepaczewski FasterWebsite.com
 */
declare(strict_types=1);

namespace fasterwebsite\perf\utils;

trait Singleton {
    /**
     * @var static
     */
    protected static $instance;

    /**
     * @return static
     */
    public static function get() {
        if(self::$instance === null) {
            self::$instance = new static();
        }
        return self::$instance;
    }
}
