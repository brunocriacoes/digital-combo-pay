<?php

final class WC_DC_FIG
{
    const VERSION      = "0.1";
    const BASE         = __DIR__ . "/..";
    const NAME         = "WooDigintalCombo";
    const ID           = "woo_digital_combo";
    const ID_MKT_PLACE = "7e704295b1ba41e88574e24830d5369a";
    const ZPK          = "zpk_prod_77hQAABdrBzAKVr8cZuaHWk8";
    const ICO          = BASE_DCP . "/static/images/logo/logo.svg";
    const METHOD_TITLE = "Woo Digital Combo";
    const HAS_FIELDS   = true;
    const HAS_DESCRIPT = "A forma mais fácil de vender através de boleto, cartão de crédito e débito recorrente via Woocommerce.";
    const TEXT_BUTTON  = "Pagar com Digital Combo";

    const ID_MKT_DEV   = "83824523b30a4f44a6231c46319c8c12";
    const ZPK_DEV      = "zpk_test_lcyUVmcv7ISdesnZe4m3w5eN";
    const SELER_DEV    = "6cf4bb1e78c6428786fc8fe6ddada3a6";
    
    static function init()
    {
        if( self::verifica_woocomerce_esta_ativo() ) :
            self::auto_load();
            self::campos_adicionais();
            self::mostrar_link_boleto_apos_finalizar();
            add_action( 'woocommerce_api_digitalcombo', [ __CLASS__, 'callback_handler' ] );
            add_filter( 'woocommerce_payment_gateways', [ __CLASS__ , "adicionar_como_meio_de_pagamento" ] );
            add_filter( 'product_type_selector', [ __CLASS__, 'add_tipo_produto_recorrente' ] );
            add_action( 'woocommerce_product_options_pricing', [ __CLASS__, 'campos_produto_recorrente' ] );
            add_action( 'woocommerce_product_options_general_product_data', function(){
                echo '<div class="options_group show_if_recorrente clear"></div>';
            } );
            add_action( 'admin_footer', [ __CLASS__, 'js_campos_produto_recorrente'] );
            add_action( 'woocommerce_process_product_meta_recorrente', [ __CLASS__, 'save_frequencia_cobranca'] );
        endif;
    }

    static function adicionar_como_meio_de_pagamento( $gateways )
    {
        $gateways[] = self::NAME;
        return $gateways;
    }   

    static function verifica_woocomerce_esta_ativo()
    {
        return in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
    }

    static function adiciona_link_de_configuracao( $links )
    {
        $url     = admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . self::ID );
        $texto   = self::METHOD_TITLE;
        $links[] = "<a href=\"$url\">$texto</a>";
        return $links;
    }

    static function auto_load()
    {
        $includes = [
            "DigitalFig",
            "WooDigintalCombo",
            "WDC_Validacao",
            "Curl",
            "Zoop",
            "Gateway",
            "WC_Product_recorente",
            "WC_Subscriptions_Product",
        ];
        foreach( $includes as $nomeClass ):
            if( ! class_exists( $nomeClass ) ) :
                include_once __DIR__ . "/$nomeClass.php";
            endif;
        endforeach;
    }
    static function callback_handler()
	{
        header( 'HTTP/1.1 200 OK' );
        global $wpdb;
        $table_perfixed = $wpdb->prefix . 'comments';
        $request        = Gateway::webHook();
        if( isset( $request['id'] ) && isset( $request['type'] ) )
        {
            $token   = $request['id'];
            $results = $wpdb->get_results("
                SELECT *
                FROM $table_perfixed
                WHERE  comment_content = 'TOKEN PEDIDO: $token'
            ");
            $pedido_id = $results[0]->comment_post_ID ?? 0;
            if( $pedido_id  )
            {
                $order = new WC_Order( $pedido_id );
            }
            switch ( $request['type'] ) 
            {
                case 'subscription.active':
                case 'transaction.succeeded':
                    $order->update_status('completed', "Pagamento confirmado");
                    break;
                case 'subscription.updated':
                    // duplica se nova
                    WC_DC_FIG::duplicar( $pedido_id );
                    // $order->update_status('completed', "Pagamento confirmado");
                    break;
                case 'subscription.deleted':
                case 'subscription.expired':
                case 'subscription.suspended':
                case 'transaction.canceled':
                case 'transaction.failed':
                case 'transaction.reversed':
                    $order->update_status('failed', "Pedido cancelado ou sumpenso");
                    break;
            }
        }
        die;
    }
    
    static function campos_adicionais()
    {
        add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

        function custom_override_checkout_fields( $fields ) {
            $fields['billing']['billing_bairro'] = [
                'type'        => 'text',
                'label'       => "bairro",
                'placeholder' => "Digite aqui seu bairro",
                'required'    => true,
                'priority'    => 61
            ];
            $fields['billing']['billing_cpf'] = [
                'type' => 'text',
                'label' => "CPF",
                'placeholder' => "000.000.000-00",
                'required' => true,
                'priority'    => 30
            ];
            return $fields;
        }
    }

    static function mostrar_link_boleto_apos_finalizar()
    {
        add_filter('woocommerce_thankyou_order_received_text', 'woo_change_order_received_text', 10, 2 );
        function woo_change_order_received_text( $str, $order ) {
            global $wpdb;
            $table_perfixed = $wpdb->prefix . 'comments';
            $id             = $order->get_id();
            $results = $wpdb->get_results("
                SELECT *
                FROM $table_perfixed
                WHERE comment_post_ID = '$id'
            
            ");
            $codigo_de_barra = array_filter( $results, function( $comment ) {		 
                return stripos( $comment->comment_content, "CODIGO DE BARRAS:" ) !== false;
            } );
            $codigo_de_barra = array_values(  $codigo_de_barra );
            if( count($codigo_de_barra) > 0 ) {
                $codigo_de_barra = $codigo_de_barra[0]->comment_content;
                $codigo_de_barra = str_replace( "CODIGO DE BARRAS:", '', $codigo_de_barra );
                $str .= "
                    <li>Seu código de barras é:  <b>$codigo_de_barra</b></li>
                ";		
            }
            $link_boleto = array_filter( $results, function( $comment ) {		 
                return stripos( $comment->comment_content, "URL BOLETO:" ) !== false;		
            } );
            $link_boleto = array_values(  $link_boleto );
            if( count($link_boleto) > 0 ) {
                $link_boleto = $link_boleto[0]->comment_content;
                $link_boleto = str_replace( "URL BOLETO:", '', $link_boleto );
                $str .= "
                    <li> 
                        Para imprimir seu boleto
                        <a href=\"$link_boleto\" target=\"_blank\">
                            Clique aqui
                        </a>
                    </li>
                ";		
            }
            return $str;
        }
    }
    static function add_tipo_produto_recorrente( $types ) 
    {
        $types['recorrente'] = "Produto por Assinatura";
       
        return $types;
    }
    static function campos_produto_recorrente()
    {
        global $product_object;
        echo "<div class='options_group show_if_recorrente'>";
        woocommerce_wp_select(
            array(
                'id'          => '_recorrente',
                'label'       =>'Tipo Contratacão',
                'value'       => $product_object->get_meta( '_recorrente', true ),
                'options' => [
                    "daily"   => "Diária",
                    "weekly"  => "Semanal",
                    "monthly" => "Mensal",
                    "annualy" => "Anual",
                ]
            )
        );
        echo "</div>";        
    }
    static function js_campos_produto_recorrente()
    {
        global $post, $product_object;

        if ( ! $post ) { return; }

        if ( 'product' != $post->post_type ) :
        return;
        endif;

        $is_advanced = $product_object && 'recorrente' === $product_object->get_type() ? true : false;

        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
            jQuery('#general_product_data .pricing').addClass('show_if_recorrente');
            <?php if ( $is_advanced ) { ?>
                jQuery('#general_product_data .pricing').show();
            <?php } ?>
            });
        </script>
        <?php        
    }
    static function save_frequencia_cobranca( $prod_id )
    {
        $_recorrente = isset( $_POST['_recorrente'] ) ? sanitize_text_field( $_POST['_recorrente'] ) : '';
        update_post_meta( $prod_id, '_recorrente', $_recorrente );
        
        update_post_meta( $prod_id, '_subscription_price', 15.80 );
        update_post_meta( $prod_id, '_subscription_period_interval', 'week' );
        update_post_meta( $prod_id, '_subscription_period_interval', 6 );
        update_post_meta( $prod_id, '_subscription_length', 7 );
        update_post_meta( $prod_id, '_subscription_trial_length', 3 );

    }

    static function duplicar( $original_order_id ) 
    {
        $original_order = new WC_Order($original_order_id);
        
        $order = wc_create_order( [
            'status'        => 'completed',
            'customer_id'   => $original_order->get_user_id(),
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

        foreach( $original_order->get_items() as $product ) :
            $id_prod = $product['product_id'];
            $is_prod = wc_get_product( $id_prod );
            $order->add_product( $is_prod, 1);
        endforeach;

        $order->calculate_totals();        
 
    }

}