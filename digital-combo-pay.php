<?php

/**
 * Plugin Name: Woo Digital Combo
 * Plugin URI: https://digitalcombo.com.br/solucoes-de-pagamento
 * Description: A forma mais fácil de vender através de boleto, cartão de crédito e débito recorrente via Woocommerce.
 * Version: 0.1
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: Digital Combo
 * Author URI: https://digitalcombo.com.br
 * Text Domain: woocommerce-gateway
 * Domain Path: /languages
 * License:
 * License URI
 * 
 * {Plugin Name} is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *  
 * {Plugin Name} is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *  
 * You should have received a copy of the GNU General Public License
 * along with {Plugin Name}. If not, see {URI to Plugin License}.
 * 
 */
 
defined( 'ABSPATH' ) || exit;



if( ! defined( 'BASE_DCP' ) ) :
	define( 'BASE_DCP', trailingslashit( WP_PLUGIN_URL ) . plugin_basename( dirname( __FILE__ ) ) );
endif;


add_action( 'woocommerce_init', function() {
	include_once __DIR__ . "/includes/DCP_Order.php";
	include_once __DIR__ . "/includes/CustomEmail.php";
	$custom_email = new CustomEmail;
} );

if( ! class_exists( 'WC_DC_FIG' ) ) :
	include_once __DIR__ . "/includes/WC_DC_FIG.php";
	add_action( 'plugins_loaded', [ "WC_DC_FIG", "init" ] );
endif;


if( ! class_exists( 'WC_Order_Subscribe' ) ) :
	include_once __DIR__ . "/includes/WC_Order_Subscribe.php";
	WC_Order_Subscribe::init();
endif;