<?php
namespace Wpsmp\Admin;

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Plans_List_Table extends \WP_List_Table {

    public function __construct() {
        parent::__construct( [
            'singular' => 'Plan',
            'plural'   => 'Plans',
            'ajax'     => false
        ] );
    }

    public function get_columns() {
        return [
            'cb'         => '<input type="checkbox" />',
            'id'         => 'ID',
            'name'       => 'Name',
            'price'      => 'Price',
            'duration'   => 'Duration',
        ];
    }

    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'id':
            case 'price':
            case 'duration':
                return esc_html( $item[ $column_name ] );
            case 'name':
                $actions = [
                    'edit' => sprintf( '<a href="?page=%s&action=%s&plan=%s">Edit</a>', esc_attr( $_REQUEST['page'] ), 'edit', $item['id'] ),
                    'delete' => sprintf( '<a href="?page=%s&action=%s&plan=%s" onclick="return confirm(\'Are you sure?\')">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', $item['id'] )
                ];
                return sprintf( '%1$s %2$s', esc_html( $item['name'] ), $this->row_actions( $actions ) );
            default:
                return print_r( $item, true );
        }
    }

    protected function column_cb( $item ) {
        return sprintf( '<input type="checkbox" name="plan[]" value="%s" />', $item['id'] );
    }

    public function prepare_items() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'wpsmp_plans';
        
        // Handle deletion
        if ( isset( $_GET['action'] ) && $_GET['action'] === 'delete' && isset( $_GET['plan'] ) ) {
            $wpdb->delete( $table_name, [ 'id' => intval( $_GET['plan'] ) ] );
        }

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
