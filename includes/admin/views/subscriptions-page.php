<div class="wrap">
    <h1 class="wp-heading-inline">User Subscriptions</h1>
    <hr class="wp-header-end">
    <form id="subscriptions-filter" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php $table->display(); ?>
    </form>
</div>
