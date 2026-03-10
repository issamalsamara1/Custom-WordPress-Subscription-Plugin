<?php
namespace Wpsmp\Frontend;

class Frontend_Init {

    public function init() {
        $shortcodes = new Shortcodes();
        $shortcodes->register();
        
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'wp_ajax_nopriv_wpsmp_process_checkout', array( $this, 'process_checkout' ) );
        add_action( 'wp_ajax_wpsmp_process_checkout', array( $this, 'process_checkout' ) );
    }

    public function enqueue_scripts() {
        wp_enqueue_script( 'stripe-js', 'https://js.stripe.com/v3/', array(), null, true );
        wp_enqueue_script( 'wpsmp-frontend', WPSMP_PLUGIN_URL . 'assets/js/frontend.js', array('jquery', 'stripe-js'), WPSMP_VERSION, true );
        
        wp_localize_script( 'wpsmp-frontend', 'wpsmp_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'stripe_pk' => get_option( 'wpsmp_stripe_public_key' )
        ) );
    }

    public function process_checkout() {
        if ( ! isset( $_POST['plan_id'] ) ) {
            wp_send_json_error( 'Invalid plan.' );
        }
        
        if ( ! is_user_logged_in() ) {
            wp_send_json_error( 'Please log in to subscribe.' );
        }
        
        $plan_id = intval( $_POST['plan_id'] );
        global $wpdb;
        $plan = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpsmp_plans WHERE id = %d", $plan_id ) );
        
        if ( ! $plan ) {
            wp_send_json_error( 'Plan not found.' );
        }
        
        $stripe = new \Wpsmp\Stripe();
        $session_url = $stripe->create_checkout_session( $plan, wp_get_current_user() );
        
        if ( $session_url ) {
            wp_send_json_success( array( 'url' => $session_url ) );
        } else {
            wp_send_json_error( 'Failed to initiate payment.' );
        }
    }
}
