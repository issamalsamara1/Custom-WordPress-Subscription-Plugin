<?php
namespace Wpsmp\Admin;

class Admin_Init {

    public function init() {
        add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'admin_init', array( $this, 'handle_plan_actions' ) );
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'Subscriptions',
            'Subscriptions',
            'manage_options',
            'wpsmp-subscriptions',
            array( $this, 'display_subscriptions_page' ),
            'dashicons-money-alt',
            26
        );

        add_submenu_page(
            'wpsmp-subscriptions',
            'Plans',
            'Plans',
            'manage_options',
            'wpsmp-plans',
            array( $this, 'display_plans_page' )
        );

        add_submenu_page(
            'wpsmp-subscriptions',
            'Settings',
            'Settings',
            'manage_options',
            'wpsmp-settings',
            array( $this, 'display_settings_page' )
        );
    }

    public function display_subscriptions_page() {
        $table = new Subscriptions_List_Table();
        $table->prepare_items();
        include WPSMP_PLUGIN_DIR . 'includes/admin/views/subscriptions-page.php';
    }

    public function display_plans_page() {
        if ( isset($_GET['action']) && ($_GET['action'] === 'add' || $_GET['action'] === 'edit') ) {
            include WPSMP_PLUGIN_DIR . 'includes/admin/views/plan-edit-page.php';
        } else {
            $table = new Plans_List_Table();
            $table->prepare_items();
            include WPSMP_PLUGIN_DIR . 'includes/admin/views/plans-page.php';
        }
    }

    public function display_settings_page() {
        include WPSMP_PLUGIN_DIR . 'includes/admin/views/settings-page.php';
    }

    public function register_settings() {
        register_setting( 'wpsmp_options_group', 'wpsmp_stripe_public_key' );
        register_setting( 'wpsmp_options_group', 'wpsmp_stripe_secret_key' );
        register_setting( 'wpsmp_options_group', 'wpsmp_stripe_webhook_secret' );
    }

    public function handle_plan_actions() {
        if ( ! isset( $_POST['wpsmp_plan_nonce'] ) || ! wp_verify_nonce( $_POST['wpsmp_plan_nonce'], 'wpsmp_save_plan' ) ) {
            return;
        }

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        global $wpdb;
        $table = $wpdb->prefix . 'wpsmp_plans';

        $name = sanitize_text_field( $_POST['plan_name'] );
        $description = sanitize_textarea_field( $_POST['plan_description'] );
        $price = floatval( $_POST['plan_price'] );
        $duration = sanitize_text_field( $_POST['plan_duration'] ); // monthly or yearly

        if ( isset( $_POST['plan_id'] ) && intval( $_POST['plan_id'] ) > 0 ) {
            $wpdb->update( $table, [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'duration' => $duration
            ], [ 'id' => intval( $_POST['plan_id'] ) ] );
        } else {
            $wpdb->insert( $table, [
                'name' => $name,
                'description' => $description,
                'price' => $price,
                'duration' => $duration
            ] );
        }

        wp_redirect( admin_url( 'admin.php?page=wpsmp-plans' ) );
        exit;
    }
}
