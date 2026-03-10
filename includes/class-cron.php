<?php
namespace Wpsmp;

class Cron {

    /**
     * Bind cron action
     */
    public function init() {
        add_action( 'wpsmp_daily_cron', array( $this, 'check_expired_subscriptions' ) );
    }

    /**
     * Find and expire old active subscriptions
     */
    public function check_expired_subscriptions() {
        global $wpdb;
        $table = $wpdb->prefix . 'wpsmp_subscriptions';
        $now = current_time( 'mysql' );

        $expired = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table WHERE end_date < %s AND status = 'active'", $now ) );

        foreach ( $expired as $sub ) {
            $wpdb->update( $table, [ 'status' => 'expired' ], [ 'id' => $sub->id ] );
            Emails::send_expiry_email( $sub->user_id, $sub->plan_id );
            Logger::log( "Subscription ID {$sub->id} has automatically expired." );
        }
    }
}
