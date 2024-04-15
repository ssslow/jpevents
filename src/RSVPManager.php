<?php

namespace JPEvents;

class RSVPManager {
    public function handle_rsvp_submission() {
        if (!isset($_POST['event_id']) || !isset($_POST['email'])) {
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit;
        }

        $event_id = intval($_POST['event_id']);
        $email = sanitize_email($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            wp_redirect($_SERVER['HTTP_REFERER']);
            exit;
        }

        $rsvps = get_post_meta($event_id, 'jpevents_rsvps', true) ? : [];
        $rsvps[] = $email;
        update_post_meta($event_id, 'jpevents_rsvps', $rsvps);

        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }
}