<?php

function add_list_order_btn_duplicate( $columns ) 
{
    $columns['wc_order_duplicate'] = 'Duplicar';
	return $columns;
}
add_filter( 'manage_edit-shop_order_columns', 'add_list_order_btn_duplicate' );


function wc_btn_order_duplicate( $column ) 
{
    global $post;
    if ( 'wc_order_duplicate' === $column ) :
		echo "
			<a href=\"javascript:void(0)\" onclick=\"globalThis.duplicar('$post->ID', this)\" class=\"button wc-action-button wc-action-button-processing processing\"> 
				Duplicar 
				#<b>$post->ID</b>
			</a>
		";
	endif;
}
add_action( 'manage_shop_order_posts_custom_column', 'wc_btn_order_duplicate' );


add_action( 'rest_api_init', function () {
	register_rest_route( 'dc-api/v1', '/order/(?P<id>\d+)', array(
	  'methods' => 'GET',
	  'callback' => 'duplicar_order',
	  'permission_callback' => false,
	), false );
} );

function duplicar_order( $param )
{
	header('Content-Type: text/html; charset=utf-8');
	$original_order = new WC_Order( $param->get_param('id') );
    $user_id        = $original_order->get_user_id();
	$order = wc_create_order( [
		'status'        => 'on-hold',
		'customer_id'   => $user_id,
		'customer_note' => '',
		'total'         => $original_order->get_total(),
	] );
	

	$address_1 = get_user_meta( $user_id, 'billing_address_1', true );
	$address_2 = get_user_meta( $user_id, 'billing_address_2', true );
	$city      = get_user_meta( $user_id, 'billing_city', true );
	$postcode  = get_user_meta( $user_id, 'billing_postcode', true );
	$country   = get_user_meta( $user_id, 'billing_country', true );
	$state     = get_user_meta( $user_id, 'billing_state', true );
	$address         = array(
		'first_name' => $original_order->get_billing_first_name(),
		'last_name'  => $original_order->get_billing_last_name(),
		'email'      => $original_order->get_billing_email(),
		'address_1'  => $address_1,
		'address_2'  => $address_2,
		'city'       => $city,
		'state'      => $state,
		'postcode'   => $postcode,
		'country'    => $country,
	);

	$order->set_address( $address, 'billing' );
	$order->set_address( $address, 'shipping' );
	$metodPayment = wc_get_payment_gateway_by_order( $param->get_param('id') );
	// var_dump( $metodPayment->settings['vencimento_boleto'] );
	// var_dump( $original_order->total );

	$day = $metodPayment->settings['vencimento_boleto'];
	$date = date_create( Date( 'Y-m-d' ) );
	date_add( $date, date_interval_create_from_date_string( "$day days" ) );
	$date =  date_format( $date, 'Y-m-d' );

	$gateway    = new Gateway;
	$usuario    = [
		"first_name"  => $original_order->get_billing_first_name(), 
		"last_name"   => $original_order->get_billing_last_name(),
		"taxpayer_id" => $original_order->get_meta('_billing_cpf'),
		"email"       => $original_order->get_billing_email(),
		"address"     => [
			"line1"        => $original_order->get_billing_address_1(), 
			"line2"        => $original_order->get_billing_address_2(), 
			"neighborhood" => $original_order->get_meta('_billing_bairro'), 
			"city"         => $original_order->get_billing_city(), 
			"state"        => $original_order->get_billing_state(), 
			"postal_code"  => $original_order->get_billing_postcode(), 
			"country_code" => "BR" 
		]
	];
	$compra = [
		'on_behalf_of'	 => $metodPayment->settings['SELLER_ID'],
		"customerID"     => get_post_meta( $user_id, "customerID_boleto", true ),
		"amount"         => str_replace( '.', '', $original_order->total ),
		"currency"       => "BRL",
		"description"    => "venda",
		"logo"           => "https://i.imgur.com/YrjT5ye.png",
		"payment_method" => [
			"expiration_date" => $date
		]
	];
	$splitRules = []; // $this->getSplitRules()
	$boleto     = $gateway->boleto( $usuario, $compra, $splitRules );

	$ID     = $boleto->payment_method->id;
	$CODE   = $boleto->payment_method->barcode;
	$BOLETO = $boleto->payment_method->url;



	$order->add_order_note(  "CODIGO DE BARRAS: $CODE", 'woothemes'  );
	$order->add_order_note(  "TOKEN PEDIDO: $ID", 'woothemes'  );
	$order->add_order_note(  "URL BOLETO: $BOLETO", 'woothemes'  );

	foreach( $original_order->get_items() as $product ) :
		$id_prod = $product['product_id'];
		$is_prod = wc_get_product( $id_prod );
		$order->add_product( $is_prod, 1);
	endforeach;
	
	$order->calculate_totals();
	return [
		"status" => $param->get_param('id'),
		"user"   => $address
	];
}

function wc_script_order_duplique_js() {
    wp_enqueue_script( 'my_custom_script', plugin_dir_url( __FILE__ ) . 'static/js/wc-order-duplicate.js', [], '1.0' );
}
add_action( 'admin_enqueue_scripts', 'wc_script_order_duplique_js' );