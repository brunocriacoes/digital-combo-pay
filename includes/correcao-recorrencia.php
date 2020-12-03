<?php

/**
 * wp-admin/admin-ajax.php?action=correcao
 */
function get_all_plans( $amout )
{ 
    $plans = [
        "25" => "52c036c08fa14d85aa89009f935dd5d0",
        "50" => "1f49998c24bb4e63b90e1a7cca472f30",
        "75" => "2143156a50084880b15bda4cb1c725ea",
        "100" => "a2c0334ece6f4fb3a9094f334f56c7cd",
        "200" => "e4b1b0ee2718455881b706807ecd5943",
        "aberto" => "efcc9daf45614b779cf32d19bc20dfe8",
    ];
    return !empty( $plans[$amout] ) ? $plans[$amout] : $plans["aberto"] ;
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
    $results = $wpdb->get_results( "SELECT order_id, customer_id, net_total, date_created FROM {$wpdb->prefix}wc_order_stats WHERE status = 'wc-completed'" );
    $results = array_map( function( $order ) use ( $costumer ) {
        $order->type = get_post_meta( $order->order_id, 'pagamento_metodo', true);
        $order->costumer_id_zoop =  !empty( $costumer[$order->customer_id] ) ? $costumer[$order->customer_id] : null;
        $order->is_recorrent =  get_post_meta( $order->order_id, 'pagamento_recorrente', true) == "Sim" ? true : false;
        return $order;
    }, $results );
    $results = array_filter( $results, function( $order ) {
        return $order->type != "Boleto";
    } );
    $results = array_map( function( $order ) {
        $month_corruent = (int) date('m') ;
        $month_payment = (int) date('m', strtotime($order->date_created) ) ;
        $is_month =  $month_corruent <= $month_payment;
        return [
            "plan" => get_all_plans( $order->net_total ),
            "on_behalf_of" => "1325ada242db4991af2df6178d5ee5aa",
            "customer" => $order->costumer_id_zoop,
            "payment_method" => "credit",
            "due_date" => $is_month ? date('Y-m-d', strtotime('+62 days', strtotime($order->date_created))) : date('Y-m-d', strtotime('+32 days', strtotime(date('Y-m-d')))),
            "due_since_date" => $is_month ? date('Y-m-d', strtotime('+32 days', strtotime($order->date_created))) : date('Y-m-d'),
            "expiration_date" => "2025-01-15",
            "amount" => number_format( $order->net_total, 2, '', ''  ),
            "currency" => "BRL",
        ];
    }, $results );
    return $results;
}
add_action( 'wp_ajax_correcao', function() {
    echo "<pre>";
    print_r( get_all_order() );
    echo "</pre>";
    die;
} );