<?php
namespace Wpsmp;

/**
 * Fired during plugin activation
 */
class Activator {

    /**
     * Create necessary tables and setup cron jobs.
     *
     * @since    1.0.0
     */
    public static function activate() {
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();

        // Create Plans table
        $table_plans = $wpdb->prefix . 'wpsmp_plans';
        $sql_plans = "CREATE TABLE $table_plans (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            name varchar(255) NOT NULL,
            description text NOT NULL,
            price decimal(10,2) NOT NULL,
            duration varchar(50) NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta( $sql_plans );

        // Create Subscriptions table
        $table_subs = $wpdb->prefix . 'wpsmp_subscriptions';
        $sql_subs = "CREATE TABLE $table_subs (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            user_id bigint(20) NOT NULL,
            plan_id bigint(20) NOT NULL,
            stripe_sub_id varchar(255) DEFAULT '' NOT NULL,
            status varchar(50) NOT NULL,
            start_date datetime NOT NULL,
            end_date datetime NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta( $sql_subs );

        // Schedule daily cron for expiry checking
        if (!wp_next_scheduled('wpsmp_daily_cron')) {
            wp_schedule_event(time(), 'daily', 'wpsmp_daily_cron');
        }
    }
}
