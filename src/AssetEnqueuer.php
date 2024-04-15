<?php

namespace JPEvents;

class AssetEnqueuer {
    public function enqueue_plugin_styles() {
        error_log('Enqueuing plugin styles');
        wp_enqueue_style('plugin-style', plugins_url('../assets/css/styles.css', __FILE__), array(), '1.0', 'all');
    }

    public function admin_scripts() {
        wp_enqueue_media();
        wp_enqueue_script(
            'jpevents-admin-script',
            plugins_url('../assets/js/admin.js', __FILE__),
            ['jquery']
        );
    }

    public function enqueue_user_scripts() {
        wp_enqueue_script('jquery');

        wp_enqueue_script(
            'jpevents-user-script',
            plugins_url('../assets/js/scripts.js', __FILE__),
            array('jquery'),
            null,
            true
        );
    }
}