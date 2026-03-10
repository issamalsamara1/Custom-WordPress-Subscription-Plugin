<?php
namespace Wpsmp\Frontend;

class Shortcodes {

    public function register() {
        add_shortcode( 'subscription_plans', array( $this, 'render_plans' ) );
        add_shortcode( 'subscription_account', array( $this, 'render_account' ) );
    }

    public function render_plans( $atts ) {
        global $wpdb;
        $plans = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}wpsmp_plans ORDER BY id ASC" );
        
        ob_start();
        include WPSMP_PLUGIN_DIR . 'includes/frontend/views/plans-shortcode.php';
        return ob_get_clean();
    }

    public function render_account( $atts ) {
        if ( ! is_user_logged_in() ) {
            return '<p>Please log in to view your subscriptions.</p>';
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpsmp_subscriptions WHERE user_id = %d AND status = 'active' ORDER BY id DESC LIMIT 1", $user_id ) );
        
        ob_start();
        include WPSMP_PLUGIN_DIR . 'includes/frontend/views/account-shortcode.php';
        return ob_get_clean();
    }
}
