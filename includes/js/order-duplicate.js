globalThis.duplicar = ( id, $el ) => 
{
    globalThis.contextBtn = $el.innerHTML
    let url = `//${window.location.hostname}/wp-json/dcp/v1/order/${id}`
    jQuery.get( url, () => document.location.reload(true) )
    document.querySelector('.js-pop-load').removeAttribute( 'hidden' )
}