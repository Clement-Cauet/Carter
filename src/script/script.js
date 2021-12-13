const ws    = new WebSocket( "" );
const oauth = new bootstrap.Modal( document.getElementById( "staticBackdrop" ), {
    keyboard: false
});

let user;

ws.onopen = ( ) => {

    oauth.show();

    //Récupére les données du formulaire de connexion
    document.getElementById( "login" ).addEventListener( "submit", async ( ) => {

        const login    = document.getElementById( "logLogin" ).value
            , password = document.getElementById( "logPassword" ).value

            , req = `code:01login:${ ( login ) }password:${( password ) }`;

        ws.send( req );

        return false;

    });
    //Récupére les données du formulaire inscription
    document.getElementById( "register" ).addEventListener( "submit", async ( ) => {

        const login    = document.getElementById( "regLogin" ).value
            , password = document.getElementById( "regPassword" ).value
            , pseudo   = document.getElementById( "regPseudo" ).value

            , req = `code:02login:${( login ) }password:${ ( password ) }pseudo:${ pseudo }`;

        ws.send( req );

        return false;

    });

    document.getElementById( "send" ).addEventListener( "submit", ( ) => {

        const input = document.getElementById( "messageContent" );

        if( !user || !input.value || !input.value.length )
            return;
        ws.send( `code:03ID:${ user.id }message:${ input.value }` );

        input.value = "";

    })

};

ws.onmessage = ( event ) => {

    // —— Try to get the code
    const code = event.data.match( /code:(\d{0,2})/ );

    console.log( code );

    // —— If the code is not found
    if ( !code )
        return;

    switch ( code[ 1 ] ) {

        case "01" : {

            const ID = event.data.match( /ID:(\d+)/ );

            console.log( ID )

            if ( !ID || ID[ 1 ] == 0 )
                return;

            console.log( "yesy")

            oauth.hide();
            user = {
                id      : ID[ 1 ],
                name    : document.getElementById( "logLogin" ).value,
            }
            document.getElementById( "chat" ).classList.remove( "d-none" );


        } break;



    }

};

ws.onclose = ( event ) => {

    console.log( 'Connection closed', event );


}
