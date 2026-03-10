<?php
/**
 * Plugin Name:       WordPress Subscription Manager
 * Plugin URI:        https://example.com/
 * Description:       A professional plugin to manage user subscriptions and Stripe payments.
 * Version:           1.0.0
 * Author:            Your Name
 * Author URI:        https://example.com/
 * Text Domain:       wpsmp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

// Define Plugin Constants
define( 'WPSMP_VERSION', '1.0.0' );
define( 'WPSMP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSMP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Require autoloader
require_once WPSMP_PLUGIN_DIR . 'includes/class-autoloader.php';

// Register Activation and Deactivation Hooks
register_activation_hook( __FILE__, array( 'Wpsmp\Activator', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Wpsmp\Deactivator', 'deactivate' ) );

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function wpsmp_init() {
    $plugin = new Wpsmp\Plugin();
    $plugin->run();
}
add_action( 'plugins_loaded', 'wpsmp_init' );
