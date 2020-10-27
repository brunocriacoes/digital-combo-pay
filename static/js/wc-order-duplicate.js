globalThis.duplicar = ( id, $el ) => 
{
    globalThis.contextBtn = $el.innerHTML
    let url = `//${window.location.hostname}/wp-json/dc-api/v1/order/${id}`
    jQuery.get( url, () => document.location.reload(true) )
    setInterval( () => {
        globalThis.animeteBtn( $el )
    }, 100 ) 
}

globalThis.stateBtn = 0 
globalThis.animeteBtn = $el => {
    let state = [ '\\', '-', '/', '-' ]
    
    if( globalThis.stateBtn > 3 ) 
    globalThis.stateBtn = 0
    
    $el.innerHTML = `${globalThis.contextBtn} ${state[globalThis.stateBtn]}`

    globalThis.stateBtn++
}