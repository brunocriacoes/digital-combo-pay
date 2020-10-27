<?php

class WC_Order_Subscribe
{
    
    function __construct()
    {
        $this->add_coluna( 'metodo', 'Metodo' );
        $this->add_coluna( 'recorrente', 'Recorrente' );
        $this->add_coluna( 'proximo_pagamento', 'P/ Pagamento' );

        $this->cols_metodo();
        $this->cols_recorrente();
        $this->cols_proximo_pagamento();
    }

    static function init()
    {
        $app =  new WC_Order_Subscribe;
    }

    function add_coluna( $indice, $text )
    {
        add_filter( 'manage_edit-shop_order_columns', function( $columns ) use ( $indice, $text ) { 
            $columns[ $indice ] = $text;
            return $columns;
        } );
    }

    function add_coluna_conteudo( $flag_cols, $callback  )
    {
        add_action( 'manage_shop_order_posts_custom_column', function( $column ) use ( $flag_cols, $callback  ) {
            global $post;
            if ( $flag_cols === $column ) :
                $callback( $post );
            endif;
        } );
    }


    function cols_metodo()
    {
        $this->add_coluna_conteudo( 'metodo', function( $post ) {
            $id_order = $post->ID;
            echo get_post_meta( $id_order , 'pagamento_metodo', true );
        } );
    }
    
    function cols_recorrente()
    {
        $this->add_coluna_conteudo( 'recorrente', function( $post ) {
            $id_order = $post->ID;
            echo get_post_meta( $id_order , 'pagamento_recorrente', true );
        } );
    }
    
    function cols_proximo_pagamento()
    {
        $this->add_coluna_conteudo( 'proximo_pagamento', function( $post ) {
            $id_order = $post->ID;
            echo get_post_meta( $id_order , 'pagamento_proximo_pagamento', true );
        } );
    }    

}