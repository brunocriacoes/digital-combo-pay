<?php

final class DigitalFig
{
    static function fields()
    {
        return [
            'enabled' => [
                'title'   => 'Ativar/Desativar',
                'type'    => 'checkbox',
                'label'   => "Pagar com " . WC_DC_FIG::METHOD_TITLE,
                'default' => 'yes'
            ],
            'title' => [
                'title'       => 'Title',
                'type'        => 'text',
                'description' => 'Este é que aparece na hora de efetuar um pagamento',
                'default'     => '',
                'desc_tip'    => true,
            ],
            'text_btn' => [
                'title'       => 'Texto Botão',
                'type'        => 'text',
                'description' => '',
                'default'     => '',
                'desc_tip'    => false,
            ],
            'description' => [
                'title'       => 'Descrição',
                'type'        => 'textarea',
                'description' => 'Esta é a descrição que aparece na hora de efetuar um pagamento',
                'default'     => '',
                'desc_tip'    => true,
            ],
            'vencimento_boleto' => [
                'title'       => 'Vencimento Boleto',
                'type'        => "number",
                'description' => 'Coloque aqui a quantidade de dias adicionais para o vencimento do boleto',
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'SELLER_ID' => [
                'title'       => "ID de Vendedor",
                'type'        => 'text',
                'description' => "insira aqui seu ID de vendedor",
                'default'     => '',
                'desc_tip'    => true,
            ],
            'pagar_como' => [
                'title'   => 'Pagar via',
                'type'    => 'select',
                'label'   => "Escolha seu meio de pagamento",
                'default' => 'cartao_credito_e_boleto',
                'options' => [
                    "cartao_credito_e_boleto" => "Cartão Crédito e Boleto",
                    "cartao_de_credito" => "Somente via Cartão Crédito",
                    "boleto" => "Somente via Boleto",
                ]
            ],
            'split' => [
                'title'   => 'Divisão',
                'label'   => 'Ativar a divisão de pagamento',
                'type'    => 'checkbox',
                'description'  => "",
                'default' => '',
                'desc_tip' => false
            ],
            'prezuiso_split' => [
                'title'   => 'Arcar/Prejuízo',
                'label'   => 'Recebedor arcar com prejuiso caso extorno',
                'type'    => 'checkbox',
                'description'  => "Ao marcar a opção o vendendor arcará com o prejuízo em caso extorno",
                'default' => 'yes',
                'desc_tip' => true
            ],
            'dias_carencia' => [
                'title'       => "Dias de Carência",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'periodo_tolerancia' => [
                'title'       => "Período de Tolerância",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],          
            'liquido_split' => [
                'title'   => 'Líquido/Bruto',
                'label'   => 'Por valor Líquido',
                'type'    => 'checkbox',
                'description'  => "Ao marcar a opção o valor será dividido pelo seu total líquido, caso contrário, será pelo valor bruto",
                'default' => 'yes',
                'desc_tip' => true
            ],
            'percentual_split' => [
                'title'       => "Recebedor 1 - Percentual de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'valor_split' => [
                'title'       => "Recebedor 1 - Valor de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'id_split' => [
                'title'       => "Recebedor 1 - ID do Recebedor",
                'type'        => 'text',
                'description' => "",
                'default'     => '',
                'desc_tip'    => true,
            ],
            'percentual_split_2' => [
                'title'       => "Recebedor 2 - Percentual de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'valor_split_2' => [
                'title'       => "Recebedor 2 - Valor de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'id_split_2' => [
                'title'       => "Recebedor 2 - ID do Recebedor",
                'type'        => 'text',
                'description' => "",
                'default'     => '',
                'desc_tip'    => true,
            ],
            'percentual_split_3' => [
                'title'       => "Recebedor 3 - Percentual de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'valor_split_3' => [
                'title'       => "Recebedor 3 - Valor de Divisão",
                'type'        => "number",
                'description' => "",
                'default'     => 0,
                'desc_tip'    => true,
            ],
            'id_split_3' => [
                'title'       => "Recebedor 3 - ID do Recebedor",
                'type'        => 'text',
                'description' => "",
                'default'     => '',
                'desc_tip'    => true,
            ],
            
           
        ];
    }
}