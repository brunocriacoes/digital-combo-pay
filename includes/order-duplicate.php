<style>
.loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    width: 100vw;
    background-color: rgba(0, 0, 0, .7);
    position: fixed;
    backdrop-filter: blur(3px);
    z-index: 99999999999;
}
.loading img {
    display: block;
    width: 50px;
}
[hidden] { 
    display: none !important;
}
</style>
<div class="loading js-pop-load" hidden>
    <img src="https://olaargentina.com/wp-content/uploads/2019/11/loading-gif-transparent-10.gif" alt="">
</div>

<?php

add_filter( 'manage_edit-shop_order_columns', function( $cols ) {
    $cols["dcp_btn_duplicate"] = "Duplicar";
    return $cols;
} );

add_action( 'manage_shop_order_posts_custom_column', function( $col_name ) {
    global $post;
    if( 'dcp_btn_duplicate' == $col_name ) :
        echo "
            <a href=\"javascript:void(globalThis.duplicar('$post->ID', this))\" class=\"button wc-action-button wc-action-button-processing processing\"> 
				Duplicar 
			</a>
        ";
    endif;
} );

add_action( 'admin_enqueue_scripts', function( $hook ) {
    if( $hook != 'edit.php') :
        return;
    endif;
    wp_enqueue_script( 'js-btn-duplicate', plugin_dir_url( __FILE__ ) . 'js/order-duplicate.js', [], '1.0', true );
} );

add_action( 'rest_api_init', function () {
	register_rest_route( 'dcp/v1', '/order/(?P<id>\d+)', array(
	  'methods' => 'GET',
	  'callback' => 'api_order_duplicate',
	  'permission_callback' => false,
	), false );
} );

function api_order_duplicate( $parameter )
{
    header('Content-Type: text/html; charset=utf-8');
    // header('Content-Type: application/json');
    $order_id = $parameter->get_param('id');
    $order = new WC_Order( $order_id );
    $config = [
		'status'        => 'pending',
		'customer_id'   => $order->get_user_id(),
		'customer_note' => "Duplicata de #$order_id",
		'total'         => $order->get_total(),
    ];

    $address_1 = get_user_meta( $order->get_user_id(), 'billing_address_1', true );
	$address_2 = get_user_meta( $order->get_user_id(), 'billing_address_2', true );
	$city      = get_user_meta( $order->get_user_id(), 'billing_city', true );
	$postcode  = get_user_meta( $order->get_user_id(), 'billing_postcode', true );
	$country   = get_user_meta( $order->get_user_id(), 'billing_country', true );
	$state     = get_user_meta( $order->get_user_id(), 'billing_state', true );
	$address         = array(
		'first_name' => $order->get_billing_first_name(),
		'last_name'  => $order->get_billing_last_name(),
		'email'      => $order->get_billing_email(),
		'address_1'  => $address_1,
		'address_2'  => $address_2,
		'city'       => $city,
		'state'      => $state,
		'postcode'   => $postcode,
		'country'    => $country,
	);

    $boleto = new_boleto($order);    
    $order_new = wc_create_order( $config ); 
    foreach( $order->get_items() as $product ) :
		$is_prod = wc_get_product( $product['product_id'] );
		$order_new->add_product( $is_prod, $product['quantity']);
	endforeach;	
    $order_new->add_order_note(  "CODIGO DE BARRAS: " . $boleto['barcode'], 'woothemes'  );
    $order_new->add_order_note(  "TOKEN PEDIDO: " . $boleto['id'], 'woothemes'  );
    $order_new->add_order_note(  "URL BOLETO: " .$boleto['link'], 'woothemes'  );
    $order_new->set_address( $address, 'billing' );
	$order_new->set_address( $address, 'shipping' );
    $order_new->calculate_totals();   
    $order_new->save();
    update_post_meta( $order_new->get_id(), "pagamento_recorrente", 'Sim' );
    $proximo_pagamento = date('d/m/Y', strtotime('+30 days', time()));
    update_post_meta( $order_new->get_id(), "pagamento_proximo_pagamento", $proximo_pagamento );
    update_post_meta( $order_new->get_id(), "pagamento_metodo", 'Boleto' );
    echo json_encode( [ "status" => true ] );
}

function new_boleto( $order )
{
    
	$date = date_create( Date( 'Y-m-d' ) );
	date_add( $date, date_interval_create_from_date_string( "5 days" ) );
    $date =  date_format( $date, 'Y-m-d' );
    
    $gateway    = new Gateway;
	$usuario    = [
		"first_name"  => $order->get_billing_first_name(), 
		"last_name"   => $order->get_billing_last_name(),
		"taxpayer_id" => $order->get_meta('_billing_cpf'),
		"email"       => $order->get_billing_email(),
		"address"     => [
			"line1"        => $order->get_billing_address_1(), 
			"line2"        => $order->get_billing_address_2(), 
			"neighborhood" => $order->get_meta('_billing_bairro'), 
			"city"         => $order->get_billing_city(), 
			"state"        => $order->get_billing_state(), 
			"postal_code"  => $order->get_billing_postcode(), 
			"country_code" => "BR" 
		]
	];
	$compra = [
		'on_behalf_of'	 => "1325ada242db4991af2df6178d5ee5aa",
		"customerID"     => get_post_meta( $order->get_user_id, "customerID_boleto", true ),
		"amount"         => str_replace( '.', '', $order->get_total() ),
		"currency"       => "BRL",
		"description"    => "venda",
		"logo"           => "https://i.imgur.com/YrjT5ye.png",
		"payment_method" => [
			"expiration_date" => $date
		]
    ];
    $boleto = $gateway->boleto( $usuario, $compra, [] );
    return [
        "id" => $boleto->payment_method->id,
        "barcode"  => $boleto->payment_method->barcode,
        "link" => $boleto->payment_method->url
    ];
}