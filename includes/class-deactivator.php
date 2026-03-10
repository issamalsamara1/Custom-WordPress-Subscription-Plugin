<?php
namespace Wpsmp;

/**
 * Fired during plugin deactivation
 */
class Deactivator {

    /**
     * Clear scheduled hooks on deactivation
     *
     * @since    1.0.0
     */
    public static function deactivate() {
        wp_clear_scheduled_hook('wpsmp_daily_cron');
    }
}
