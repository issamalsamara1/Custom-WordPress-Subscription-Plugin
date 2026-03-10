<div class="wpsmp-account-container">
    <?php if ( $subscription ) : ?>
        <?php
        global $wpdb;
        $plan = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}wpsmp_plans WHERE id = %d", $subscription->plan_id ) );
        ?>
        <h3>Your Active Subscription</h3>
        <p><strong>Plan:</strong> <?php echo esc_html( $plan ? $plan->name : 'Unknown' ); ?></p>
        <p><strong>Status:</strong> <?php echo esc_html( ucfirst( $subscription->status ) ); ?></p>
        <p><strong>Start Date:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $subscription->start_date ) ) ); ?></p>
        <p><strong>Expiry Date:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $subscription->end_date ) ) ); ?></p>
    <?php else : ?>
        <p>You do not have any active subscriptions.</p>
    <?php endif; ?>
</div>
