<?php

namespace JPEvents;

class EventUtils {
    public static function calculate_next_occurrence($start_date, $recurrence_type, $interval, $end_date) {
        $current_date = strtotime($start_date);
        $today = strtotime(date('Y-m-d'));
        $day_increment = $interval * ($recurrence_type == 'daily' ? 1 : ($recurrence_type == 'weekly' ? 7 : 0));

        while ($current_date < $today) {
            $current_date = strtotime("+{$day_increment} days", $current_date);

            if ($recurrence_type == 'monthly') {
                $current_date = strtotime("+{$interval} month", $current_date);
            } elseif ($recurrence_type == 'yearly') {
                $current_date = strtotime("+{$interval} year", $current_date);
            }

            if ($current_date > strtotime($end_date)) {
                return false;
            }
        }

        return date('Y-m-d', $current_date);
    }
}