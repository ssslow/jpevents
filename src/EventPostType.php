<?php

namespace JPEvents;

class EventPostType {
    public function register_post_type() {
        $args = [
            'labels' => [
                'name' => __('Events', 'jpevents'),
                'singular_name' => __('Event', 'jpevents'),
            ],
            'public' => true,
            'has_archive' => true,
            'supports' => ['title', 'editor', 'thumbnail'],
            'rewrite' => ['slug' => 'events'],
            'taxonomies' => array('category', 'post_tag'),
        ];

        register_post_type('jpevents_event', $args);
    }

    public function add_meta_boxes() {
        add_meta_box(
            'jpevents_event_details',
            __('Event Details', 'jpevents'),
            [$this, 'event_details_callback'],
            'jpevents_event',
            'normal',
            'high'
        );
    }

    public function event_details_callback($post) {
        wp_nonce_field('jpevents_event_nonce_action', 'jpevents_event_nonce');

        // Retrieve existing values from the database
        $event_date = get_post_meta($post->ID, 'jpevents_date', true);
        $event_time = get_post_meta($post->ID, 'jpevents_time', true);
        $event_location = get_post_meta($post->ID, 'jpevents_location', true);
        $event_image = get_post_meta($post->ID, 'jpevents_image', true);

        // Fields for event details
        echo '<label for="jpevents_date">' . __('Event Date', 'jpevents') . '</label>';
        echo '<input type="date" id="jpevents_date" name="jpevents_date" value="' . esc_attr($event_date) . '" />';

        echo '<label for="jpevents_time">' . __('Event Time', 'jpevents') . '</label>';
        echo '<input type="time" id="jpevents_time" name="jpevents_time" value="' . esc_attr($event_time) . '" />';

        echo '<label for="jpevents_location">' . __('Event Location', 'jpevents') . '</label>';
        echo '<input type="text" id="jpevents_location" name="jpevents_location" value="' . esc_attr($event_location) . '" />';

        // For the image, use WordPress media uploader
        echo '<label for="jpevents_image">' . __('Event Image', 'jpevents') . '</label>';
        echo '<input type="text" id="jpevents_image" name="jpevents_image" value="' . esc_attr($event_image) . '" />';
        echo '<button type="button" id="jpevents_image_button">' . __('Upload Image', 'jpevents') . '</button>';

        $post_id = get_the_ID();

        $recurrence_type = get_post_meta($post_id, 'jpevents_recurrence_type', true);
        $recurrence_interval = get_post_meta($post_id, 'jpevents_recurrence_interval', true);
        $recurrence_end_date = get_post_meta($post_id, 'jpevents_recurrence_end_date', true);

        echo '<h4>Event Recurrence</h4>';
        echo '<label for="jpevents_recurrence_type">Recurrence Type:</label>';
        echo '<select id="jpevents_recurrence_type" name="jpevents_recurrence_type">';
        echo '<option value="none"' . selected($recurrence_type, 'none', false) . '>None</option>';
        echo '<option value="daily"' . selected($recurrence_type, 'daily', false) . '>Daily</option>';
        echo '<option value="weekly"' . selected($recurrence_type, 'weekly', false) . '>Weekly</option>';
        echo '<option value="monthly"' . selected($recurrence_type, 'monthly', false) . '>Monthly</option>';
        echo '<option value="yearly"' . selected($recurrence_type, 'yearly', false) . '>Yearly</option>';
        echo '</select>';

        echo '<label for="jpevents_recurrence_interval">Interval:</label>';
        echo '<input type="number" id="jpevents_recurrence_interval" name="jpevents_recurrence_interval" value="' . esc_attr($recurrence_interval ?: '1') . '" min="1" />';

        echo '<label for="jpevents_recurrence_end_date">End Date:</label>';
        echo '<input type="date" id="jpevents_recurrence_end_date" name="jpevents_recurrence_end_date" value="' . esc_attr($recurrence_end_date) . '" />';


        $rsvps = get_post_meta($post->ID, 'jpevents_rsvps', true);
        echo '<h3>RSVP List</h3>';
        if (!empty($rsvps)) {
            echo '<ul>';
            foreach ($rsvps as $rsvp) {
                echo '<li>' . esc_html($rsvp) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No RSVPs yet.</p>';
        }
    }

    public function save_event_details($post_id) {
        if (isset($_POST['jpevents_date'])) {
            update_post_meta($post_id, 'jpevents_date', sanitize_text_field($_POST['jpevents_date']));
        }
        if (isset($_POST['jpevents_time'])) {
            update_post_meta($post_id, 'jpevents_time', sanitize_text_field($_POST['jpevents_time']));
        }
        if (isset($_POST['jpevents_location'])) {
            update_post_meta($post_id, 'jpevents_location', sanitize_text_field($_POST['jpevents_location']));
        }
        if (isset($_POST['jpevents_image'])) {
            update_post_meta($post_id, 'jpevents_image', esc_url_raw($_POST['jpevents_image']));
        }

        if (isset($_POST['jpevents_recurrence_type'])) {
            update_post_meta($post_id, 'jpevents_recurrence_type', sanitize_text_field($_POST['jpevents_recurrence_type']));
        }
        if (isset($_POST['jpevents_recurrence_interval'])) {
            update_post_meta($post_id, 'jpevents_recurrence_interval', intval($_POST['jpevents_recurrence_interval']));
        }
        if (isset($_POST['jpevents_recurrence_end_date'])) {
            update_post_meta($post_id, 'jpevents_recurrence_end_date', sanitize_text_field($_POST['jpevents_recurrence_end_date']));
        }
    }
}