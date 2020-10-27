<link rel="stylesheet" href="<?= BASE_DCP ?>/static/css/card.css">
<div class="card_form">
    <div>
        <?php if( $modo_de_pagamento == "cartao_credito_e_boleto" ): ?>
            <div class="escolha_tipo">
                <!-- <input type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="cartao_debito" id="c_3" checked  hidden>
                <label for="c_3">
                    <img src="<?= BASE_DCP ?>/static/images/icone/card.svg" alt="card">
                    <small>Debito</small>
                </label> -->
                <input type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="cartao_credito" id="c_1" checked  hidden>
                <label for="c_1">
                    <img src="<?= BASE_DCP ?>/static/images/icone/card.svg" alt="card">
                    <small>Credito</small>
                </label>
                <input type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="boleto" id="c_2" hidden>
                <label for="c_2">
                    <img src="<?= BASE_DCP ?>/static/images/icone/barcode.svg" alt="barcode">
                    <small>Boleto</small>
                </label>
            </div>
            <script> globalThis.opcao_pagamento( 'cartao_credito' ) </script>
        <?php else: ?>
            <?php if( $modo_de_pagamento == "cartao_de_credito" ): ?>
                <div class="escolha_tipo">
                    <!-- <input type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="cartao_debito" id="c_3" checked  hidden>
                    <label for="c_3">
                        <img src="<?= BASE_DCP ?>/static/images/icone/card.svg" alt="card">
                        <small>Debito</small>
                    </label> -->
                    <input type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="cartao_credito" id="c_1" checked  hidden>
                    <label for="c_1">
                        <img src="<?= BASE_DCP ?>/static/images/icone/card.svg" alt="card">
                        <small>Cartão</small>
                    </label>
                </div>
                <script> globalThis.opcao_pagamento( 'cartao_credito' ) </script>
                <?php else: ?>
                    <div class="escolha_tipo">
                        <input checked type="radio" oninput="globalThis.opcao_pagamento( this.value )" name="type_pagamento" value="boleto" id="c_2" hidden>
                        <label for="c_2">
                            <img src="<?= BASE_DCP ?>/static/images/icone/barcode.svg" alt="barcode">
                            <small>Boleto</small>
                        </label>
                    </div>
                    <script> globalThis.opcao_pagamento( 'boleto' ) </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>

</div>
<div id="card_digital_combo" hidden>
    <div class="card">
        <div class="card__branch">
            <img src="<?= BASE_DCP ?>/static/images/chip.png" alt="">
            <span></span>
            <img src="<?= BASE_DCP ?>/static/images/logo/icone-white.svg" alt="">
        </div>
        <div class="card_number" id="vNumber">0000 0000 0000 0000</div>
        <div class="card__valid_cvv">
            <div>
                <span>VALID</span>
                <b id="vValid" >02/29</b>
            </div>
            <div>
                <span>CVV</span>
                <b id="vCvv">123</b>
            </div>
        </div>
        <div class="card_name" id="vName">FULANO DA SILVA</div>
    </div>
    <div class="card_form" >
        <div>
            <label for="">Número<b>*</b></label>
            <input type="text" value="" name="card_number" id="iNumber" oninput="globalThis.card_number()" placeholder="0000 0000 0000 0000" require>
        </div>
        <div>
            <label for="">Nome<b>*</b></label>
            <input type="text" value="" name="card_name" id="iName" placeholder="DIGITA AQUI SEU NOME" oninput="globalThis.card_name()" require>
        </div>
        <div class="card_grid_cvv_valid">
            <div>
                <label for="">Data de Validade<b>*</b></label>
                <input type="text" name="card_valid" value="" id="iValid" placeholder="MM/AA" oninput="globalThis.card_valid()" require>
            </div>
            <div>
                <label for="">CVV<b>*</b></label>
                <input type="text" value="" name="card_cvv" id="iCvv" placeholder="123" oninput="globalThis.card_cvv()" require>
            </div>
        </div>
    </div>
</div>
<script src="<?= BASE_DCP ?>/static/js/card.js"></script>