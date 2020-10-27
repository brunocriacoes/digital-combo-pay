<?php

class Zoop extends Curl {
    private $idMarketplace;
    private $keyZpk;
    private $api;

    function __construct() {
        $this->idMarketplace = WC_DC_FIG::ID_MKT_PLACE;
        $this->keyZpk        = WC_DC_FIG::ZPK;
        // $this->idMarketplace = '83824523b30a4f44a6231c46319c8c12';
        // $this->keyZpk        = 'zpk_test_lcyUVmcv7ISdesnZe4m3w5eN';
        $this->api           = 'https://api.zoop.ws/';
    }

    public function transactions( $arr, $url, $version = false, $type = false ) {
        $version = $version  ?? '' ? 'v2' : 'v1';
        $fullUrl = "{$this->api}{$version}/marketplaces/{$this->idMarketplace}/{$url}";
        if( $url == 'subscriptions' ) { $this->post( $fullUrl, $arr, [], $this->keyZpk, true ); }

        return $this->post( $fullUrl, $arr, [], $this->keyZpk, $type );
    }

    public function boletoOrder( $info, $customer ) {
        
        unset($info['customerID']);
        $info['payment_type'] = "boleto";
        $info['customer']     = $customer;

        return $this->transactions( $info, 'transactions', false, true );
    }
}