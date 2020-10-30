<?php

class CustomEmail
{
    function __construct() 
    {
        $this->add_link_boleto();
        $this->preview_email();
    }
    public function preview_email()
    {
        add_action( 'wp_ajax_previewemail', function() {
            global $order;
            $orderId  = 213;
            $order    = new WC_Order($orderId);
            wc_get_template( 'emails/email-header.php', array( 'order' => $order ) );
            echo "<style>";
                wc_get_template( 'emails/email-styles.php', array( 'order' => $order ) );
            echo "</style>";
            wc_get_template( 'emails/email-order-details.php', array( 'order' => $order ) );
            wc_get_template( 'emails/email-footer.php', array( 'order' => $order ) );

        } );
    }
    public function add_link_boleto()
    {
        add_action( 'woocommerce_email_before_order_table', function( $order ) {
            $more        = new DCP_Order( $order->id );
            $barcode     =  $more->get_barcode();
            $boleto_link = $more->get_boleto();
            if( $more->get_type() == "Boleto" ):
                echo "
                    <center>
                        <p>baixe agora seu boleto</p>
                        <a 
                            href=\"{$boleto_link}\"
                            style=\"
                                border: 3px solid #666;
                                display: block;
                                padding: 20px;
                                text-decoration: none;
                                color: #666;
                            \"
                            target=\"_blank\"
                        > 
                            BAIXAR BOLETO
                        </a>			
                        <br>
                        <b>ou copie o codigo de barras</b>
                        <span>{$barcode}</span>
                        <br>
                        <br>
                    </center>
                ";
            endif;
        } );
    }
}