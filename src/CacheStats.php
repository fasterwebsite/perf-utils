<?php
/**
 * @author Maciej Klepaczewski <matt@fasterwebsite.com>
 * @link https://fasterwebsite.com/
 * @copyright Copyright (c) 2021, Maciej Klepaczewski FasterWebsite.com
 */
declare(strict_types=1);

namespace fasterwebsite\perf\utils;

/**
 * Registers hit/miss
 */
class CacheStats {
    use Singleton;

    /**
     * @var array<string, string>
     */
    protected array $groupPatterns;

    /**
     * <cache-type, <group, <type-of-operation, counter>>>
     * For example:
     * - <wp_object_cache, <terms, <miss, 1>>>
     * - <wp_object_cache, <terms, <hit, 1>>>
     * - <get_the_terms, <product_{id}, <miss, 3>>
     * - <magento-turpentine, products, <hit, 1>>>
     * @var array<string, array<string, array<string, int>>>
     */
    private array $tickers;

    /**
     * CacheStats constructor.
     * @param array<string, string> $groupPatterns
     */
    public function __construct(array $groupPatterns = []) {
        $this->tickers = [];
        $this->groupPatterns = $groupPatterns;
    }

    public function tick(string $cacheType, string $operationType, ?string $group) : void {
        $group = $this->getGroupName($group);
        if(!isset($this->tickers[$cacheType][$operationType][$group])) {
            $this->tickers[$cacheType][$operationType][$group] = 0;
        }
        $this->tickers[$cacheType][$operationType][$group]++;
    }

    public function miss(string $cacheType, ?string $group) : void {
        $this->tick($cacheType, 'miss', $group);
    }

    public function hit(string $cacheType, ?string $group) : void {
        $this->tick($cacheType, 'hit', $group);
    }

    public function set(string $cacheType, ?string $group) : void {
        $this->tick($cacheType, 'set', $group);
    }

    /**
     * @return array<string, array<string, int>>
     */
    public function getTickers(): array {
        return $this->tickers;
    }

    /**
     * Maps groups with IDs into buckets with identifier replaced by {id}. For
     * example, product_12345 -> product_{id}. It makes sense to group these
     * into "product_{id}" as they represent the same larger group.
     * @param string|null $group
     * @return string
     */
    protected function getGroupName(?string $group) : string {
        if($group === null) {
            return 'null';
        }

        foreach($this->groupPatterns as $id => $pattern) {
            if(preg_match($pattern, $group)) {
                $group = $id;
                break;
            }
        }

        return $group;
    }
}
