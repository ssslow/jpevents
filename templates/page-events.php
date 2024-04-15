<?php
/**
 * Template Name: Events Listing Page
 */

get_header();

$event_categories = get_terms('category', array('hide_empty' => false));

echo '<div class="event-categories">';
echo '<a href="#" id="all-events">All</a> ';
foreach ($event_categories as $category) {
    if ($category->slug !== 'uncategorised') {
        echo '<a href="' . esc_url(add_query_arg('jpevents', $category->slug)) . '">' . esc_html($category->name) . '</a> ';
    }
}
echo '</div>';

$args = array(
    'post_type' => 'jpevents_event',
    'posts_per_page' => -1,
    'meta_key' => 'jpevents_date',
    'orderby' => 'meta_value',
    'order' => 'ASC',
    'meta_query' => array(
        array(
            'key' => 'jpevents_date',
            'value' => date('Y-m-d', strtotime("-1 year")),
            'compare' => '>=',
            'type' => 'DATE'
        ),
    ),
);

$events_query = new WP_Query($args);

if ($events_query->have_posts()) :
    echo '<h2>Upcoming Events</h2>';
    echo '<div class="events-listing">';

    while ($events_query->have_posts()) : $events_query->the_post();
        $event_date = get_post_meta(get_the_ID(), 'jpevents_date', true);
        $event_time = get_post_meta(get_the_ID(), 'jpevents_time', true);
        $event_location = get_post_meta(get_the_ID(), 'jpevents_location', true);
        $recurrence_type = get_post_meta(get_the_ID(), 'jpevents_recurrence_type', true);
        $recurrence_interval = get_post_meta(get_the_ID(), 'jpevents_recurrence_interval', true);
        $recurrence_end_date = get_post_meta(get_the_ID(), 'jpevents_recurrence_end_date', true);

        $next_occurrence = $event_date;
        $recurring = false;
        if ($recurrence_type !== '' && $recurrence_type !== 'none') {
            if (class_exists('JPEvents\EventUtils')) {
                $next_occurrence = JPEvents\EventUtils::calculate_next_occurrence($event_date, $recurrence_type, $recurrence_interval, $recurrence_end_date);
            }

            $recurring = true;
        }

        // Check if the next occurrence is within a valid range to display
        if ($next_occurrence && strtotime($next_occurrence) >= time() && strtotime($next_occurrence) <= strtotime('+1 year')) {
            echo '<div class="event" data-category="' . esc_attr(join(', ', wp_get_post_terms(get_the_ID(), 'category', ['fields' => 'slugs']))) . '">';

            $categories = get_the_terms(get_the_ID(), 'category');
            if (!empty($categories)) {
                $category_names = wp_list_pluck($categories, 'name');
                echo '<h2 class="event-category">' . esc_html(join(', ', $category_names)) . '</h2>';
            }

            echo '<h4 class="event-title">' . get_the_title() . '</h4>';
            echo '<p class="event-date">Date: ' . esc_html($next_occurrence) . ($recurring ? ' (recurring)' : '') . '</p>';
            echo '<p class="event-time">Time: ' . esc_html($event_time) . '</p>';
            echo '<p class="event-location">Location: ' . esc_html($event_location) . '</p>';
            echo '<a href="' . get_permalink() . '" class="event-details-link">View details</a>';
            echo '</div>';
        }
    endwhile;

    echo '</div>';

    wp_reset_postdata();
else :
    echo '<p>No upcoming events found.</p>';
endif;

get_footer();