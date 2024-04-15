<?php

namespace JPEvents;

class EventImporter {
    public function import_events_from_api() {
        error_log('Starting import from API');
        $api_url = 'https://www.bcfc.co.uk/wp-json/afz/v1/fixtures?status=fixture';
        $response = wp_remote_get($api_url);

        if (is_wp_error($response)) {
            return;
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body);

        if (empty($data)) {
            return;
        }

        foreach ($data as $event) {
            $existing_post_id = $this->get_existing_event($event->gameId);

            $datetime = date_create($event->date);
            if ($datetime === false) {
                error_log('Failed to parse date: ' . $event->date);
                continue;
            }

            $wp_date = date_format($datetime, 'Y-m-d');
            $wp_time = date_format($datetime, 'H:i:s');

            $post_data = [
                'ID'           => $existing_post_id ?: 0,
                'post_title'   => $event->homeTeam->name . ' vs ' . $event->awayTeam->name,
                'post_content' => '',
                'post_status'  => 'publish',
                'post_type'    => 'jpevents_event',
                'meta_input'   => [
                    'jpevents_date'     => $wp_date,
                    'jpevents_time'     => $wp_time,
                    'jpevents_location' => $event->venue,
                ],
            ];

            $post_id = wp_insert_post($post_data);

            $this->assign_event_category($event, $post_id);

            $this->set_event_image($event, $post_id);
        }
    }

    private function assign_event_category($event, $post_id) {
        $category_name = $event->category;
        $category = get_term_by('name', $category_name, 'category');

        if (!$category) {
            $category = wp_insert_term($category_name, 'category');
        }

        if (!is_wp_error($category)) {
            wp_set_post_terms($post_id, [$category->term_id], 'category');
        }
    }

    private function set_event_image($event, $post_id) {
        $image_url = $event->homeTeam->logo;

        if ($image_url) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            // Temporarily add an action to retrieve the ID of the sideloaded image.
            add_filter('media_sideload_image', function($html, $id, $attachment_id, $size) {
                return $attachment_id;
            }, 10, 4);

            // Sideload image and retrieve attachment ID.
            $image_id = media_sideload_image($image_url, $post_id, null, 'id');

            // If the image was successfully downloaded, set it as the post thumbnail.
            if (!is_wp_error($image_id) && is_numeric($image_id)) {
                update_post_meta($post_id, '_thumbnail_id', $image_id);
            } else {
                // Log error if there was an issue.
                error_log('Failed to sideload image from: ' . $image_url);
                if (is_wp_error($image_id)) {
                    error_log('WP_Error: ' . $image_id->get_error_message());
                }
            }

            // Remove the filter after use to avoid unintended effects on other uploads.
            remove_filter('media_sideload_image', 'id');
        }
    }

    private function get_existing_event($gameId) {
        $query = new \WP_Query([
            'meta_key'       => 'jpevents_game_id',
            'meta_value'     => $gameId,
            'post_type'      => 'jpevents_event',
            'posts_per_page' => 1,
        ]);

        if ($query->have_posts()) {
            return $query->posts[0]->ID;
        }

        return null;
    }
}