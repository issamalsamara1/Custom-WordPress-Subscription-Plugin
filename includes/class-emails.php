<?php
namespace Wpsmp;

class Emails {

    /**
     * Send email to user upon subscription expiry
     */
    public static function send_expiry_email( $user_id, $plan_id ) {
        $user = get_userdata( $user_id );
        if ( ! $user ) return;

        global $wpdb;
        $plan = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}wpsmp_plans WHERE id = %d", $plan_id ) );
        $plan_name = $plan ? $plan->name : 'Unknown Plan';

        $to = $user->user_email;
        $subject = 'Your Subscription has expired';
        $message = "Hello {$user->display_name},\n\nYour subscription to {$plan_name} has expired. Please renew to continue accessing premium features.\n\nThank you.";

        wp_mail( $to, $subject, $message );
    }
}
