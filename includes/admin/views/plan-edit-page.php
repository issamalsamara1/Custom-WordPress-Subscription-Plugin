<?php
global $wpdb;
$plan = null;
if ( isset( $_GET['plan'] ) ) {
    $plan = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}wpsmp_plans WHERE id = %d", intval( $_GET['plan'] ) ) );
}
?>
<div class="wrap">
    <h1><?php echo $plan ? 'Edit Plan' : 'Add New Plan'; ?></h1>
    <form method="post" action="">
        <?php wp_nonce_field( 'wpsmp_save_plan', 'wpsmp_plan_nonce' ); ?>
        <?php if ( $plan ) : ?>
            <input type="hidden" name="plan_id" value="<?php echo intval( $plan->id ); ?>" />
        <?php endif; ?>
        
        <table class="form-table">
            <tr>
                <th scope="row"><label for="plan_name">Plan Name</label></th>
                <td><input type="text" name="plan_name" id="plan_name" class="regular-text" required value="<?php echo $plan ? esc_attr( $plan->name ) : ''; ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="plan_description">Description</label></th>
                <td><textarea name="plan_description" id="plan_description" class="large-text" rows="5" required><?php echo $plan ? esc_textarea( $plan->description ) : ''; ?></textarea></td>
            </tr>
            <tr>
                <th scope="row"><label for="plan_price">Price</label></th>
                <td><input type="number" step="0.01" name="plan_price" id="plan_price" class="regular-text" required value="<?php echo $plan ? esc_attr( $plan->price ) : ''; ?>" /></td>
            </tr>
            <tr>
                <th scope="row"><label for="plan_duration">Duration</label></th>
                <td>
                    <select name="plan_duration" id="plan_duration">
                        <option value="monthly" <?php selected( $plan ? $plan->duration : '', 'monthly' ); ?>>Monthly</option>
                        <option value="yearly" <?php selected( $plan ? $plan->duration : '', 'yearly' ); ?>>Yearly</option>
                    </select>
                </td>
            </tr>
        </table>
        <?php submit_button( 'Save Plan' ); ?>
    </form>
</div>
