<?php

class Zoop extends Curl {
    private $idMarketplace;
    private $keyZpk;
    private $api;

    function set_log( $log ) {
		$data = date('d/m/Y H:i ->');
		file_put_contents( __DIR__ . '/../.log', "{$data} {$log} \n", FILE_APPEND );
	}

    function __construct() {        
        $this->idMarketplace = ENV['KEY'];
        $this->keyZpk        = ENV['ZPK'];
        $this->api           = ENV['API'];
    }

    public function transactions( $arr, $url, $version = false, $type = false ) {
        $version = $version  ?? '' ? 'v2' : 'v1';
        $fullUrl = "{$this->api}{$version}/marketplaces/{$this->idMarketplace}/{$url}";
        if( $url == 'subscriptions' ) { $this->post( $fullUrl, $arr, [], $this->keyZpk, true ); }
        $playload = $this->post( $fullUrl, $arr, [], $this->keyZpk, $type );
        $requeste = json_encode( $arr );
        $response = json_encode( json_decode($playload) );
        $this->set_log( "POST /{$url} -> {$requeste}" );
        $this->set_log( "RES /{$url} -> {$response}" );
        return $playload;
    }

    public function boletoOrder( $info, $customer ) {
        
        unset($info['customerID']);
        $info['payment_type'] = "boleto";
        $info['customer']     = $customer;

        return $this->transactions( $info, 'transactions', false, true );
    }
}