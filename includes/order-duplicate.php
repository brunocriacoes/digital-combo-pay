<?php if( is_admin() ): ?>
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
<?php endif; ?>
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
			'status'        => 'wc-on-hold',
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
		$order_new->add_order_note(  "URL BOLETO: " . $boleto['link'], 'woothemes'  );
		$order_new->set_address( $address, 'billing' );
		$order_new->set_address( $address, 'shipping' );
		$order_new->calculate_totals();   
		$order_new->save();

		update_post_meta( $order_new->get_id(), "pagamento_recorrente", 'Sim' );
		$proximo_pagamento = date('d/m/Y', strtotime('+30 days', time()));
		update_post_meta( $order_new->get_id(), "pagamento_proximo_pagamento", $proximo_pagamento );
		update_post_meta( $order_new->get_id(), "pagamento_metodo", 'Boleto' );

		update_post_meta( $order_new->get_id(), 'ORDER_BARCODE', $boleto['barcode'] );
		update_post_meta( $order_new->get_id(), 'ORDER_BOLETO', $boleto['link'] );
		update_post_meta( $order_new->get_id(), 'ORDER_REF', $boleto['id'] );
		update_post_meta( $order_new->get_id(), 'pagamento_metodo', 'Boleto' );

		
		evendas( 
			$order_new->get_id(), 
			$order->get_total(), 
			$order->get_billing_first_name(), 
			$order->get_billing_last_name(), 
			$order->get_billing_email(),
			$order->get_billing_phone(),
			$boleto['barcode'],
			$boleto['link'], 
			$boleto['id'] 
		);

		// "82999776698"		
		echo json_encode( [ "status" => true ] );
		sendEmail( $order->get_billing_email(), 'Boleto', $order_new );
	}

	function new_boleto( $order )
	{
		
		$date = date_create( Date( 'Y-m-d' ) );
		date_add( $date, date_interval_create_from_date_string( "5 days" ) );
		$date =  date_format( $date, 'Y-m-d' );
		$combo = new WooDigintalCombo();

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
			'on_behalf_of'	 => $combo->id_vendedor,
			"customerID"     => get_post_meta( $order->get_user_id, "customerID_boleto", true ),
			"amount"         => str_replace( '.', '', $order->get_total() ),
			"currency"       => "BRL",
			"description"    => "venda",
			"logo"           => "https://i.imgur.com/YrjT5ye.png",
			"payment_method" => [
				"expiration_date" => $date,
				"body_instructions" => [ 
					"Boleto exclusivo para Doação. Este Boleto será utilizado para doação espontânea. ",
					"Não é uma cobrança",
					"Não Cobra juros nem multa",
					"Seja providência de Deus para nós."
				]
			]
		];
		$boleto = $gateway->boleto( $usuario, $compra, [] );
		return [
			"id" => $boleto->payment_method->id,
			"barcode"  => $boleto->payment_method->barcode,
			"link" => $boleto->payment_method->url
		];
	}

function order_log( $log ) {
	$data = date('d/m/Y H:i ->');
	file_put_contents( __DIR__ . '/../.log', "{$data} {$log} \n", FILE_APPEND );
}

function evendas( $id, $total, $first_name, $last_name, $email, $phone, $barcode, $boleto_link, $ref ) 
{
	
	$playload = [
		"id"           => $id,
		"number"       => $id,
    	"status"       => 'on-hold',
    	"date_created" => date('Y-m-d'),
        "total"        => $total,
        "barcode"      => $barcode,
        "boleto_link"  => $boleto_link,
        "ref"          => $ref,
        "billing" => [
            "first_name" => $first_name,
            "last_name"  => $last_name,
            "email"      => $email,
            "phone"      => $phone,
        ],
        "payment_method" =>  'digital_combo_pay_boleto',
        "meta_data" => [
			[
				"id" => 007,
				"key" => "ORDER_BARCODE",
				"value" => $barcode
			],
			[
				"id" => 007,
				"key" => "ORDER_BOLETO",
				"value" => $boleto_link
			],
			[
				"id" => 007,
				"key" => "ORDER_REF",
				"value" => $ref
			],
			[
				"id" => 007,
				"key" => "pagamento_metodo",
				"value" => 'digital_combo_pay_boleto'
			]
		]
	];
	$defaults = [
		CURLOPT_POST           => true,
		CURLOPT_HEADER         => 0,
		CURLOPT_URL            => 'http://servicos.e-vendas.net.br/api/woocommerce/33804c49-42c0-4488-9e10-8ba5ab2b357e',
		CURLOPT_POSTFIELDS     => json_encode( $playload ),
		CURLOPT_HTTPHEADER     => [ 'Content-Type:application/json' ],
		CURLOPT_RETURNTRANSFER => 1
	];    
	$con = curl_init();
	curl_setopt_array( $con, $defaults );
	$ex = curl_exec($con);
	curl_close($con);
	order_log( "RES EVENDAS ->" . $ex );
	
}

function get_custom_email_html( $order, $heading = false, $mailer ) {
	$template = 'emails/customer-on-hold-order.php';
	return wc_get_template_html( $template, array(
		'order'         => $order,
		'email_heading' => $heading,
		'sent_to_admin' => false,
		'plain_text'    => false,
		'email'         => $mailer
	) );

}
function sendEmail( $email, $subject, $order )
{
	$mailer = WC()->mailer();	
	$content = get_custom_email_html( $order, $subject, $mailer );
	$headers = "Content-Type: text/html\r\n";	
	$mailer->send( $email, $subject, $content, $headers );
}
