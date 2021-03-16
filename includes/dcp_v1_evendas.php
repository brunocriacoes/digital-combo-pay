<?php

add_action( 'rest_api_init', function () {
	register_rest_route( 'dcp/v1', '/evendas', array(
	  'methods' => 'POST',
	  'callback' => 'integracao_evendas',
	  'permission_callback' => false,
	), false );
} );

function integracao_evendas( $param ) 
{
    // header('Content-Type: text/html; charset=utf-8');
	$request = file_get_contents('php://input');
    $request = json_decode( $request );
    $request = adapter_resquest_webhook_wc( $request );
    $defaults = [
        CURLOPT_POST           => true,
        CURLOPT_HEADER         => 0,
        CURLOPT_URL            => 'http://servicos.e-vendas.net.br/api/woocommerce/33804c49-42c0-4488-9e10-8ba5ab2b357e',
        // CURLOPT_FRESH_CONNECT  => 1,
        // CURLOPT_RETURNTRANSFER => 1,
        // CURLOPT_FORBID_REUSE   => 1,
        // CURLOPT_TIMEOUT        => 12,
        CURLOPT_POSTFIELDS     => json_encode( $request ),
        // CURLOPT_SSL_VERIFYPEER => 0,
        // CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER     => [ 'Content-Type:application/json' ]
    ];    
    $con = curl_init();
    curl_setopt_array( $con, $defaults );
    $ex = curl_exec($con);
    curl_close($con);
	return $ex;
}



function get_payment_method( $request )
{
    $filtro = array_filter( $request->meta_data, function( $meta ) {
        return $meta->key == 'pagamento_metodo';
    } );
    $filtro = array_values( $filtro );
    $filtro = $filtro[0];
    return $filtro->value == "Boleto" ?  'digital_combo_pay_boleto' : 'digital_combo_pay_cartao';
}

function adapter_get_meta( $flag, $metas )
{
    foreach( $metas as $meta ) :
        if ($meta->key == $flag ) :
            return $meta->value;
        endif;
    endforeach;
    return null;
}

function adapter_resquest_webhook_wc( $request )
{
    return [
		"id"           => $request->id,
		"number"       => $request->id,
    	"status"       => $request->status,
    	"date_created" => $request->date_created,
        "total"        => $request->total,
        "barcode"      => adapter_get_meta( 'ORDER_BARCODE', $request->meta_data ),
        "boleto_link"  => adapter_get_meta( 'ORDER_BOLETO', $request->meta_data ),
        "ref"          => adapter_get_meta( 'ORDER_REF', $request->meta_data ),
        "billing" => [
            "first_name" => $request->billing->first_name,
            "last_name"  => $request->billing->last_name,
            "email"      => $request->billing->email,
            "phone"      => $request->billing->phone,
        ],
        "payment_method" =>  get_payment_method( $request ),
        "meta_data" => array_values( array_filter( $request->meta_data, function( $meta ) {
            return in_array( $meta->key, ['ORDER_BARCODE', 'ORDER_BOLETO', 'ORDER_REF', 'pagamento_metodo'] );
        } ) )
	];
}