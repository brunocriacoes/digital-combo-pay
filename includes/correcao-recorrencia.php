<?php

/**
 * wp-admin/admin-ajax.php?action=correcao
 */


function get_all_plans()
{
    // monthly
    return [
        "25" => "000000001",
        "50" => "000000001",
        "75" => "000000001",
        "100" => "000000001",
        "200" => "000000001",
        "custon" => "000000001",
    ];
}
function get_all_custumers()
{
    global $wpdb;
    $render = [];
    $results = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}postmeta WHERE meta_key = 'customerID_credit'", OBJECT );
    foreach( $results as $costumer ) {
        $render[$costumer->post_id] = $costumer->meta_value;
    }
    return $render;
}
function get_all_order()
{
    global $wpdb;
    $costumer = get_all_custumers();
    $results = $wpdb->get_results( "SELECT order_id, customer_id, net_total FROM {$wpdb->prefix}wc_order_stats WHERE status = 'wc-completed'" );
    $results = array_map( function( $order ) use ( $costumer ) {
        $order->type = get_post_meta( $order->order_id, 'pagamento_metodo', true);
        $order->costumer_id_zoop =  !empty( $costumer[$order->customer_id] ) ? $costumer[$order->customer_id] : null;
        $order->is_recorrent =  get_post_meta( $order->order_id, 'pagamento_recorrente', true) == "Sim" ? true : false;
        return $order;
    }, $results );
    $results = array_filter( $results, function( $order ) {
        return $order->type != "Boleto";
    } );
    return $results;
}

add_action( 'wp_ajax_correcao', function() {
    var_dump( get_all_order() );
    die;
} );