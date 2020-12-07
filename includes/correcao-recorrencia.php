<?php

/**
 * wp-admin/admin-ajax.php?action=correcao
 * 7e704295b1ba41e88574e24830d5369a
 * zpk_prod_77hQAABdrBzAKVr8cZuaHWk8
 * order_id;date;price;payment_method_id;Customer_id;transaction_id
 */

function get_data()
{
    $csv = "9544 ;18 de outubro de 2020;25;acd5a7bd0dcb4e10ae7c1659295602fa;b84b20c36b62480eafd4467d749a203e;74426c82b4d14a16958af6ad7a116b7a
    9616;19 de outubro de 2020;50;37716fb194374cf29ee94996c33fac18;e62d5d29521846dcaaad5b486b6eb3fa;a02177c5095f4955ab5ac71b9e22e5c5
    9619;19 de outubro de 2020;100;f8fb9dac9cfc4cd9b9941c7b56520b29;c2f2588e1dcb44558ede48647ecf4792;655a71d018b44d3bbf3c4dace2c6b5f3
    9642;23 de outubro de 2020;25;b57bfa2ae16a4ed1867be3cd7d1b5fd1;f72bb644ad4245c886fd9ce0f3f4966e;031f83598ec044e3b7c96e317f46265c
    9646;23 de outubro de 2020;50;e3d378062e0d4905a321c0a1587bc56a;839341412c2c4f71a0077b1abc566571;57d5e42d897941a193974bdbcc5e5c03
    9647;23 de outubro de 2020;25;57e36ebedeac4f45b15ec1ee7f99eea2;791603a5d2304d75bce6d72ff846ffd0;6e536fdf198b40519c027fe153f4c833
    9651;24 de outubro de 2020;25;d1e730ab15a04f79b7fa200f8bea1f34;c3eb6766d85f47aea94c09ee9f45d68f;2245758088bd477ab73d19e152de18d8
    9655;24 de outubro de 2020;25;338fdef8b92541ebb731856df89c11bd;e2aa2e407ede4b4aa2a1dd5fc4cea62c;4d13d8666a284dca81a7145e80abdeaa
    9659;24 de outubro de 2020;25;fcdcec0a2bef47968b459ebc005ce2bb;f4b2cfb257f34e0d817f83b5481d7168;02b884c36a294060a904f46909f35615
    9689;27 de outubro de 2020;25;320596d2175d419cb0f1ab7ce7aa55de;faba53e710714e7cbd94e4860c545859;05c6911c6300405689fbc09efd26bdb6
    9691;28 de outubro de 2020;25;ac7842b61ff1404d8fce7f080c6a68b5;613a11d5e31244349c989f0964047845;84113034d7fe4310be7065917c290e5b
    9715;1 de novembro de 2020;25;101b1f20835c405da64ce5d09a42fedb;65ee03537ff941feac5c1c03e6366694;72ac174203e64a0593f688b4f3913700
    9718;1 de novembro de 2020;25;152d313a69814c3a96229ab30b9d52bb;5de48c0094f64b12b68bce1abed760d5;e3161f99f18b45098f0e47b6285a49d4
    9734;3 de novembro de 2020;25;a2848ba477fd469f834b5d3b553c13a1;c7360634a65545b7bd14ab601c77d89f;2aa181958fac47c281d7f254b14c70a9
    9751;5 de novembro de 2020;100;7c86d14a7d954653bff1522d7e08b376;7b64054d5a344e4e820c9bfab6c2e4e3;9f5b5b30674e4733b96f830969f35ea3
    9752;5 de novembro de 2020;25;4fa30ba9f84e485aa71727bc252a297e;e409bf296b7343bf9863dd01a2cae94a;953749d3a6d940b49a3edca23c29db21
    9759;6 de novembro de 2020;25;00545061323949258a473c0858f2eea8;03c3331bdb1a497cae357e8b2a09f79b;d59ea8f14e534321ba06af18d501f2b8
    9774;7 de novembro de 2020;25;3e36ae4d65774a58af8f2683b1eb6c47;99616c92b41e4ab899a89fa0ac30087a;723cce4ac7aa436a8c19878761322573
    9799;12 de novembro de 2020;25;e3050117f581472e8972fbe0c39513f3;516ad20a067a4eb0a010de64f8090aad;52d8d563f1ba4b4b8836cbab332e97ae
    9801;12 de novembro de 2020;25;bc06f2863fa4449f99898fb29d566526;c448f88648b44ea0bf313e34e7216e13;428f5d7abff5430b818ac46fb768bb4f
    9859;13 de novembro de 2020;25;f5bdb4b51a464037a483174e432ab50d;b7ce33b28e6546fbba2c987cefc62f8b;4deb8b7bbb9a4e1d98efbf01f81ac8c3
    9861;13 de novembro de 2020;200;dbdf611e559241a68f83ab09342b54ec;2636ccc444184749a8c243ffab6013ff;7efe0657a1f74704a191abfd55ebd01e
    9871;14 de novembro de 2020;50;b578a93fbaf24c23b1d0291e8c66512a;1f2c4daa5a604301a138db4d37bd370b;90566d656fa54199a995f9792d9c269f
    9874;14 de novembro de 2020;25;96e8ea5a0dba4195bd4802480ec5c1bb;3f267c9b345042eb8a842cb23f449d91;9c99cc0843604221871c6aa3b3df685b
    9915;18 de novembro de 2020;75;52a3296f9bc64f008bde27db8d87ba13;5bd177c038a8418ab2c9e033abd877b7;dd1b7414aaa447f085df2b07f84b2bf0
    9917;18 de novembro de 2020;50;4fd97e33cd0346a6ba12a50615a64a64;5bc2be7126d44d5780d94f9ae44c75b5;e54e0259c4494d1b87ef015c5ef0d5bf
    9944;21 de novembro de 2020;25;67957aa341ba4408bdf9560fd9404cb4;6c7e66a71c744df69f2ee0b84ce83797;6e9be12f20dc480a88c2f1a7cdf13a64
    9945;21 de novembro de 2020;50;478ac4ae471845c8ab821f3f817b5470;100019cadd56467796c5f11dc9daaec4;ee4816e5d85243ddb27639a96210d6e4
    9946;22 de novembro de 2020;25;0921c237f4c34c6abbc8f199f564126b;6616729604994a21884130f261f2f831;d89577caae8b4c83b108677532ebaec9
    9998;24 de novembro de 2020;25;47488cf128e846808562dd7bd9421f6d;2c41612412364c8390c74f852c101fcd;6660ae1cb4524e3cb756b6106ef97aba
    9999;24 de novembro de 2020;25;e742f793fe9e4b9088a433840d21907b;47036bddd7ab454db902ed0b396bd0fa;a20c82abe74f4c1d883152a9b6ade207
    10001;25 de novembro de 2020;25;56184d6086504ce5a51fb5f9230cf918;88f3dfe2de664a269259e53b6889aeec;fbfca9c375d44fe0810c0761aad9d2fd
    10006;26 de novembro de 2020;25;bca77403d00d4f0aa6215e946ba87ca7;95fd97e4fc7141a8b05f73a9ee09e56a;6311f2e34db546a3ab4af74ab379e889
    10014;28 de novembro de 2020;25;733aaf637c8843dda563d6ee32bceb6c;f28854d4d2664af3a2a939c34d62d16c;595dbff489c54dee9a1f38687a554719
    10018;29 de novembro de 2020;25;fece55ebb3254558b2c784d5c3de54e9;849dc7ab4efd4fdb9776aca7eed1e465;24fe2de39d994203a60a08a7c5ad10a8
    10020;30 de novembro de 2020;50;e25ea5dc993649258b112b75e5ca679e;14086e17cd4d4b7f84e001cadc3d71d8;9e5f00e4d3f04b038c857c8af21e50eb
    10021;30 de novembro de 2020;100;89ac34c87a344e7787357102138bb99a;db8564a3e0594b5ca06ea203206c2ecb;7bcafa82d70c4760aa508c77afe544a4
    10035;30 de novembro de 2020;25;e4e84251cbf648b287c0480067dcb349;d86765899ef947eea964b8800699215d;41bbbd6cfbbe4528a248f75ea4ec0dc0
    10052;02 de dezembro de 2020;100;4dd08e1dee14415eb21f829fc58cde2b;977b8a3da83b4a3097a70605058e1aff;54a5b47e06ff423dae20250eb0f7642e
    10058;02 de dezembro de 2020;50;d1361cd5dbd84bb6a57993c31fcf7deb;8f235ade86c947f8aa7b7f3248f534a2;2ea825e230be41a3960c8cdfc4d8fb22
    10059;02 de dezembro de 2020;75;8aaf4403e1744a82865783fd1128efeb;36f0f9f1bacf46c7adf1055983a20a69;6261759dd81d4117b78680d3e724cf4d
    10079;03 de dezembro de 2020;25;a02035ec82b84a1abada960e35fa2928;27589697610449ad9c7cd11d2444925b;591080a78920440080f793a9c3f97c1b
    10080;03 de dezembro de 2020;25;30adee9461894ebfa767324cecbe31d9;2e9da1fd84554267a5598598aa711221;bfc5def93b96487b9772acd6a0fd3f93";
    $data = explode( ';', $csv );
}
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
function get_custumer( $os_ref_zoop )
{
    $url = "https://api.zoop.ws/v1/marketplaces/7e704295b1ba41e88574e24830d5369a/transactions/{$os_ref_zoop}";
   
    $defaults = array(
        CURLOPT_POST           => 0,
        CURLOPT_HEADER         => 0,
        CURLOPT_URL            => $url,
        CURLOPT_FRESH_CONNECT  => 1,
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_FORBID_REUSE   => 1,
        CURLOPT_TIMEOUT        => 12,
        CURLOPT_USERPWD        => "zpk_prod_77hQAABdrBzAKVr8cZuaHWk8:",
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_HTTPHEADER     => [ 'Content-Type' => 'application/json; charset=UTF-8', 'accept' => 'application/json' ]
    );    
    $request = curl_init();
    curl_setopt_array($request, $defaults);
    $result = curl_exec($request);
    curl_close($request);
    var_dump($result);
    return $os_ref_zoop;
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
    // $results = array_filter( $results, function( $order ) {
    //     return $order->type != "Boleto";
    // } );
    $results = array_map( function( $order ) {
        $month_corruent = (int) date('m') ;
        $month_payment = (int) date('m', strtotime($order->date_created) ) ;
        $is_month =  $month_corruent <= $month_payment;
        return [
            "plan" => get_all_plans( $order->net_total ),
            "on_behalf_of" => "1325ada242db4991af2df6178d5ee5aa",
            "customer" => get_custumer( get_post_meta( $order->order_id, 'ORDER_REF', true ) ),
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