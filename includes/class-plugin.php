<?php
namespace Wpsmp;

class Plugin {

    /**
     * Run the plugin, load all components
     *
     * @since    1.0.0
     */
    public function run() {
        // Initialize Admin
        if ( is_admin() ) {
            $admin = new Admin\Admin_Init();
            $admin->init();
        }
        
        // Initialize Frontend
        $frontend = new Frontend\Frontend_Init();
        $frontend->init();

        // Initialize Webhooks (Stripe)
        $stripe = new Stripe();
        $stripe->init();

        // Initialize Cron tasks
        $cron = new Cron();
        $cron->init();
    }
}
