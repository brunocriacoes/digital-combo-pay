<?php
class DC_Subscription
{
    function get_type_to_day( string $type )
    {
        $list_of_types = [
            "daily"   => 365,
            "weekly"  => (int) 365 / 7,
            "monthly" => 12,
            "annualy" => 1,
        ];
        return !empty( $list_of_types[ $type ] ) ? $list_of_types[ $type ] : 12;
    }

    function register( string $type, int $id_os ) {

    }

    function unregister( int $id_os ) 
    {}
}

