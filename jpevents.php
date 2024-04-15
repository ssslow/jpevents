<?php
/**
 * Plugin Name: JP Events
 * Plugin URI: https://jpeventseventcalendarplugin.com/jpevents
 * Description: A simple event calendar plugin to add, edit, and manage events.
 * Version: 1.0
 * Author: Johan Preller
 * Author URI: https://jpeventseventcalendarplugin.com
 */

require_once __DIR__ . '/vendor/autoload.php';

use JPEvents\AssetEnqueuer;
use JPEvents\EventImporter;
use JPEvents\EventPostType;
use JPEvents\RSVPManager;

class JPEvents {
    private $asset_enqueuer;
    private $event_importer;
    private $event_post_type;
    private $rsvp_manager;

    public function __construct() {
        error_log('JPEvents plugin loaded');

        $this->event_post_type = new EventPostType();
        $this->event_importer = new EventImporter();
        $this->rsvp_manager = new RSVPManager();
        $this->asset_enqueuer = new AssetEnqueuer();

        $this->setup_hooks();
    }

    public function setup_hooks() {
        add_action('init', [$this->event_post_type, 'register_post_type']);
        add_action('add_meta_boxes', [$this->event_post_type, 'add_meta_boxes']);
        add_action('save_post', [$this->event_post_type, 'save_event_details']);

        add_action('jpevents_daily_event_import', [$this->event_importer, 'import_events_from_api']);

        add_action('wp_enqueue_scripts', [$this->asset_enqueuer, 'enqueue_plugin_styles']);
        add_action('admin_enqueue_scripts', [$this->asset_enqueuer, 'admin_scripts']);
        add_action('wp_enqueue_scripts', [$this->asset_enqueuer, 'enqueue_user_scripts']);

        add_action('admin_post_nopriv_handle_rsvp', [$this->rsvp_manager, 'handle_rsvp_submission']);
        add_action('admin_post_handle_rsvp', [$this->rsvp_manager, 'handle_rsvp_submission']);

        add_filter('posts_join', [$this, 'jpevents_search_join']);
        add_filter('posts_where', [$this, 'jpevents_search_where']);
        add_filter('posts_distinct', [$this, 'jpevents_search_distinct']);

        register_activation_hook(__FILE__, [$this, 'schedule_event']);
        register_deactivation_hook(__FILE__, [$this, 'unschedule_event']);
    }

    public static function schedule_event() {
        error_log('Scheduling event');
        if (!wp_next_scheduled('jpevents_daily_event_import')) {
            wp_schedule_event(time(), 'daily', 'jpevents_daily_event_import');
        }
    }

    public static function unschedule_event() {
        $timestamp = wp_next_scheduled('jpevents_daily_event_import');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'jpevents_daily_event_import');
        }
    }

    public function jpevents_search_join( $join ) {
        global $wpdb;

        if ( is_search() ) {
            $join .= "
            LEFT JOIN {$wpdb->postmeta} ON {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id ";
        }

        return $join;
    }

    public function jpevents_search_where( $where ) {
        global $wpdb;

        if ( is_search() ) {
            $where .= " OR (
                ({$wpdb->postmeta}.meta_key LIKE 'jpevents_date' OR
                {$wpdb->postmeta}.meta_key LIKE 'jpevents_time' OR
                {$wpdb->postmeta}.meta_key LIKE 'jpevents_location' OR
                {$wpdb->postmeta}.meta_key LIKE 'jpevents_description') AND
                {$wpdb->postmeta}.meta_value LIKE '%" . esc_sql( get_query_var('s') ) . "%'
            )";
        }

        return $where;
    }

    public function jpevents_search_distinct( $where ) {
        if ( is_search() ) {
            return "DISTINCT";
        }

        return $where;
    }

}

new JPEvents();
