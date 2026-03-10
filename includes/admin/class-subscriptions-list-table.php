<?php
namespace Wpsmp\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Subscriptions_List_Table extends \WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => 'Subscription',
            'plural'   => 'Subscriptions',
            'ajax'     => false
        ] );
    }

    public function get_columns() {
        return [
            'cb'         => '<input type="checkbox" />',
            'id'         => 'ID',
            'user'       => 'User',
            'plan'       => 'Plan',
            'status'     => 'Status',
            'start_date' => 'Start Date',
            'end_date'   => 'End Date'
        ];
    }

    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id':
            case 'status':
            case 'start_date':
            case 'end_date':
                return esc_html( $item[ $column_name ] );
            case 'user':
                $user = get_userdata( $item['user_id'] );
                return $user ? esc_html( $user->user_login ) : 'Unknown';
            case 'plan':
                global $wpdb;
                $plan = $wpdb->get_row( $wpdb->prepare( "SELECT name FROM {$wpdb->prefix}wpsmp_plans WHERE id = %d", $item['plan_id'] ) );
                return $plan ? esc_html( $plan->name ) : 'Unknown';
            default:
                return print_r( $item, true );
        }
    }

    protected function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="subscription[]" value="%s" />', $item['id'] );
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpsmp_subscriptions';
        $per_page = 20;
        $current_page = $this->get_pagenum();
        $total_items = $wpdb->get_var( "SELECT COUNT(id) FROM $table_name" );

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page
        ] );

        $this->_column_headers = [ $this->get_columns(), [], $this->get_sortable_columns() ];
        $offset = ( $current_page - 1 ) * $per_page;
        $this->items = $wpdb->get_results( "SELECT * FROM $table_name ORDER BY id DESC LIMIT $per_page OFFSET $offset", ARRAY_A );
    }
}
