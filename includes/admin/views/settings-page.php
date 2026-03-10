<div class="wrap">
    <h1>Settings</h1>
    <form method="post" action="options.php">
        <?php settings_fields( 'wpsmp_options_group' ); ?>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Stripe Public Key</th>
                <td><input type="text" name="wpsmp_stripe_public_key" value="<?php echo esc_attr( get_option( 'wpsmp_stripe_public_key' ) ); ?>" class="regular-text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Stripe Secret Key</th>
                <td><input type="password" name="wpsmp_stripe_secret_key" value="<?php echo esc_attr( get_option( 'wpsmp_stripe_secret_key' ) ); ?>" class="regular-text" /></td>
            </tr>
            <tr valign="top">
                <th scope="row">Stripe Webhook Secret</th>
                <td><input type="password" name="wpsmp_stripe_webhook_secret" value="<?php echo esc_attr( get_option( 'wpsmp_stripe_webhook_secret' ) ); ?>" class="regular-text" /></td>
            </tr>
        </table>
        
        <?php submit_button(); ?>
    </form>
</div>
