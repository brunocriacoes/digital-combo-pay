<?php
    /* Template Name: thanks-page */
    global $order;
    $order = new WC_Order( $_GET['id'] );
    $more  = new DCP_Order( $_GET['id'] );
    $is_boleto   = $more->get_type() == "Boleto";
    $link_boleto = $more->get_boleto();
    $code_boleto = $more->get_barcode();
    // var_dump($order);
 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/style.css">
    <title>Comunidade Bethânia</title>
</head>

<body>
    <header>
        <img id="logo" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/logo.jpeg" alt="Logo de Bethânia">
    </header>
    <div id="block1">
        <img class="verif ic" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/verificado.png" alt="">
        <h3>Obrigado por sua generosidade!</h3>
    </div>
    <?php if($is_boleto): ?>
        <div id="block2">
        <p><b>Clique abaixo para acessar o seu boleto.</b></p>
        <a href="<?= $link_boleto ?>" target="_blank" class="boleto"><img src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/boleto.png" alt="Icone Boleto" class="bolIco"><b>VER MEU BOLETO</b></a>
        <p>ou copie o <b>código de barras:</b></p>
        <br>
        <div id="inpContainer">
            <input id="texto" type="text" value="<?= $code_boleto ?>">
            <button id="btnCopiador"><b>Copiar</b></button>
        </div>

        <p><b>Importante: Este boleto é uma contribuição espontânea e não gera protesto.</b></p>
    </div>
    <?php else: ?>
        <div id="block2">
            <h2><b>Sua doação foi recebida com sucesso, Deus te abençoe!.</b></h2>
        </div>
    <?php endif; ?>

    <div id="block3">
        <h3>Obrigado, <?php echo $order->get_billing_first_name(); ?>!</h3>
        <br>
        <p>"A providência de Deus se manifesta por meio de você!", Pe. Léo, sjc</p>
        <br>
        <p>Ajude-nos na missão de<br>
            Restaurar Vidas! Compartilhe esta causa!</p>
        <br>
        <div>
            <a id="faceShare" class="box" href="https://www.facebook.com/sharer/sharer.php?u=https://doacoesbethania.com.br">Compartilhar <br>no Facebook <img class="fb" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/facebook.png" alt=""></a>
            <a id="whatsShare" class="box" href="https://web.whatsapp.com/send?text=https://doacoesbethania.com.br" data-action="share/whatsapp/share">Compartilhar <br>no Whatsapp <img class="whats" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/whatsapp.png" alt=""></a>
        </div>
        <a id="doacao" href="https://doacoesbethania.com.br"><b>Fazer nova Doação</b></a>
    </div>
    <footer>
        <div>
            <p>Comunidade Bethânia - CNPJ: 00.816.354/001-09 | Endereço: Estr, Municipal Bethânia , 400,
                Caixa Postal 71 - Timbezinho, São Jão Batista - SC - CEP: 88240-000 | Para dúvidas e cancelamentos entre em contato com nossa central de relacionamento no telefone <img class="footIco" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/telefone.png" alt="Ícone de telefone">(48) 3265-4416
                ou pelo e-mail: <img class="footIco" src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/Ícones/email.png" alt="Ícone de E-mail"> soubetania@bethania.com.br| Em até 30 dias após o processamento da transação, realizaremos o ressarcimento integral dos valores doados.</p>
        </div>
    </footer>
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/thanks-page/script.js"></script>
</body>

</html>