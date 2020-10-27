

globalThis.card_number = () => 
{
    let $iNumber = document.querySelector( "#iNumber" )
    let $vNumber = document.querySelector( "#vNumber" )
    let mascara  = $iNumber.value
    mascara      = mascara.replace( /\D/gi, '' )
    mascara      = mascara.replace( /(\d{4})(.*)/gi, '$1 $2' )
    mascara      = mascara.replace( /(\d{4}\s)(\d{4})(.*)/gi, '$1$2 $3' )
    mascara      = mascara.replace( /(\d{4}\s)(\d{4}\s)(\d{4})(.*)/gi, '$1$2$3 $4' )
    mascara      = mascara.replace( /(\d{4}\s)(\d{4}\s)(\d{4}\s)(\d{4})(.*)/gi, '$1$2$3$4' )
    if( mascara.length > 0 ) {
        $vNumber.innerHTML = mascara
        $iNumber.value     = mascara
    } else {
        $vNumber.innerHTML = "0000 0000 0000 0000"
        $iNumber.value     = mascara
    }
}

globalThis.card_name = () => 
{
    let $iName = document.querySelector( "#iName" )
    let $vName = document.querySelector( "#vName" )  
    if( $iName.value.length > 0 ) {
        $vName.innerHTML = $iName.value
    } else {
        $vName.innerHTML = "Fulano da Silva"
    }
}

globalThis.card_valid = () => 
{
    let $iValid = document.querySelector( "#iValid" )
    let $vValid = document.querySelector( "#vValid" )
    let mascara = $iValid.value
    mascara      = mascara.replace( /\D/gi, '' )
    mascara      = mascara.replace( /(\d{2})(\d{2})(.*)/gi, '$1/$2' )
    if( mascara.length > 0 ) {
        $vValid.innerHTML = mascara
        $iValid.value     = mascara
    } else {
        $vValid.innerHTML = "02/29"
    }
}

globalThis.card_cvv = () => 
{
    let $iCvv = document.querySelector( "#iCvv" )
    let $vCvv = document.querySelector( "#vCvv" )
    let mascara = $iCvv.value
    mascara      = mascara.replace( /\D/gi, '' )
    mascara      = mascara.replace( /(\d{3})(.*)/gi, '$1' )
    if( mascara.length > 0 ) {
        $vCvv.innerHTML = mascara
        $iCvv.value     = mascara
    } else {
        $vCvv.innerHTML = "123"
    }
}

globalThis.opcao_pagamento = tipo => {
    let $card_digital_combo = document.querySelector( "#card_digital_combo" )
    if( tipo == "cartao_credito" || tipo == "cartao_debito" ) {
        $card_digital_combo.removeAttribute( 'hidden' )
    } else {
        $card_digital_combo.setAttribute( 'hidden', '' )    
    }
}
