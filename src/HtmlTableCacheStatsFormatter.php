<?php
/**
 * @author Maciej Klepaczewski <matt@fasterwebsite.com>
 * @link https://fasterwebsite.com/
 * @copyright Copyright (c) 2021, Maciej Klepaczewski FasterWebsite.com
 */
declare(strict_types=1);

namespace fasterwebsite\perf\utils;


class HtmlTableCacheStatsFormatter implements CacheStatsFormatter {

    public function format(CacheStats $cacheStats): string {
        $buffer = [
            '<table class="cache-stats" style="background-color: white; text: black;">',
            '<thead>',
                '<tr>',
                '<th>Type</th>',
                '<th>Event</th>',
                '<th>Group</th>',
                '<th>Counter</th>',
                '</tr>',
            '</thead>',
            '<tbody>'
        ];

        $stats = $cacheStats->getTickers();
        foreach($stats as $cacheType => $events) {
            foreach($events as $event => $groups) {
                foreach ($groups as $group => $counter) {
                    $buffer[] = "<tr>
                    <td class=\"cache-stats-cache-type\">{$cacheType}</td>
                    <td class=\"cache-stats-event\">{$event}</td>
                    <td class=\"cache-stats-group\">{$group}</td>
                    <td class='cache-stats-counter'>{$counter}</td>
                    </tr>";
                }
            }
        }

        $buffer[] = '</tbody>';
        $buffer[] = '</table>';

        return implode("\n", $buffer);
    }
}
