<?php 

class DCP_Order
{
    public $order_id;
    public $ID;
    function __construct( $order_id )
    {
        $this->order_id = $order_id;
        $this->ID       = $order_id;
    }

    public function set_barcode( $code )
    {
        update_post_meta( $this->order_id, 'ORDER_BARCODE', $code );
    }

    public function get_barcode()
    {
        return get_post_meta( $this->order_id, 'ORDER_BARCODE', true );
    }

    public function set_boleto( $link )
    {
        update_post_meta( $this->order_id, 'ORDER_BOLETO', $link );
    }

    public function get_boleto()
    {
        return get_post_meta( $this->order_id, 'ORDER_BOLETO', true );
    }
    
    public function set_ref( $link )
    {
        update_post_meta( $this->order_id, 'ORDER_REF', $link );
    }
    
    public function get_ref()
    {
        return get_post_meta( $this->order_id, 'ORDER_REF', true );
    }
    
    public function set_type( $type )
    {
        update_post_meta( $this->order_id, 'pagamento_metodo', $type );
    }    
    
    public function get_type()
    {
        return get_post_meta( $this->order_id, 'pagamento_metodo', true );
    }
}