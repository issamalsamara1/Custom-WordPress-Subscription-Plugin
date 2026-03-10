<div class="wrap">
    <h1 class="wp-heading-inline">Subscription Plans</h1>
    <a href="?page=<?php echo esc_attr( $_REQUEST['page'] ); ?>&action=add" class="page-title-action">Add New</a>
    <hr class="wp-header-end">
    <form id="plans-filter" method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php $table->display(); ?>
    </form>
</div>
