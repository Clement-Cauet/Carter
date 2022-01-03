const cardContent   = document.getElementById( "cardContent" );
const cardList      = document.getElementById( "allCard" );


let editMode = false;
let currentCard = [ false, 0, 0, "" ];

// Websocket connection to the reader
const socket = new WebSocket( "ws://192.168.64.103:4457" );
let isAdmin  = false;
let verify = false;

// Websocket status : message
socket.addEventListener( "message", ( event ) => {

    // Get message parts
    const [ hasRead, cardType, cardSerial, data ] = event.data.split( "," );
    currentCard = [ hasRead, cardType, cardSerial, data ];

    if ( hasRead === "false" ) {
        cardContent.innerHTML = `No card detected, please put your card on the reader`;
        editMode = false;
        isAdmin  = false;
        verify   = false;

        cardList.innerHTML = "";

    } else if ( editMode === false ) {

        !verify && fetch( `${window.location.href}api/verifyUser.php` , {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify( {
                ID      : currentCard[ 2 ],
            } )
        } )

        .then( ( data ) => data.text() )
        .then( async ( data ) => {

            if ( data != 1 )
                return;

            try {


                const users = await fetch( `${window.location.href}api/getUsers.php` ).then( ( data ) => data.json() );
                const cards = await fetch( `${window.location.href}api/getAllCard.php` ).then( ( data ) => data.json() )

                const select = document.createElement( "select" );
                for ( const user of users ) {
                    const option = document.createElement( "option" );
                    option.value = user.ID;
                    option.innerHTML = user.Name;
                    select.add( option );
                }
                select.selectedIndex = 0;

                const seperator = document.createElement( "hr" );
                seperator.classList.add( "my-4" );
                cardList.appendChild( seperator );

                for ( const card of cards ) {

                    const cardRender = document.createElement( "div" );
                    cardRender.innerHTML = `
                        <div class="card" style="width: 18rem;" id="${card.ID}">
                            <div class="card-body">
                                <h6 class="card-subtitle mb-2 text-muted">${card.nom} ${card.prenom}</h6>
                                <h5 class="card-title">${card.ID} - <small> Type ${ card.CardType } <small></h5>
                                <h6 class="card-subtitle mb-2 text-muted">Informations</h6>
                            </div>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">Numéro de série : ${ card.ID }</li>
                                <li class="list-group-item" id="editableContent">${ card.Data}</li>
                            </ul>
                            <div class="card-body">
                                <a id="edit" class="card-link" onclick="removeCard( ${ card.ID } )">Supprimer la carte</a>
                            </div>
                        </div>
                    `;

                    cardList.appendChild( cardRender );

                }

            } catch( err ) {

                console.error( err );

            }

        } ).catch( ( error ) => console.log( error ) );

        verify = true;

        cardContent.innerHTML = `
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <h5 class="card-title">Carte insérée - <small> Type ${ cardType } <small></h5>
                <h6 class="card-subtitle mb-2 text-muted">Informations</h6>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Numéro de série : ${ cardSerial }</li>
                <li class="list-group-item" id="editableContent">${data}</li>
            </ul>
            <div class="card-body">
                <a id="edit" class="card-link" onclick="edit()">Modifier le contenue</a>
                <a id="save" class="card-link d-none" onclick="save( socket )">Enregistrer</a>
            </div>
        </div>
        `
    }

    console.log( event.data );

});

function edit( ) {

    editMode = !editMode;

    const editableContent = document.getElementById( "editableContent" )
        , saveButton      = document.getElementById( "save" );

    if ( editMode ) {

        editableContent.innerHTML = `
            <div class="form-floating">
                <textarea class="form-control" id="editArea" style="height: 100px">${ currentCard[ 3 ] }</textarea>
                <label for="floatingTextarea2">New Content</label>
            </div>
        `;

        saveButton.classList.remove( "d-none" );

    } else {

        editableContent.innerHTML = currentCard[ 3 ];
        saveButton.classList.add( "d-none" );

    }

}

function save( socket ) {

    socket.send( document.getElementById( "editArea" ).value );

    fetch( `${window.location.href}api/saveContent.php` , {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify( {
            ID      : currentCard[ 2 ],
            CardType: currentCard[ 1 ],
            Data    : document.getElementById( "editArea" ).value
        } )
    } )
        .then( ( data ) => {
            currentCard[ 3 ] = document.getElementById( "editArea" ).value;
            edit( );
        } )
        .catch( ( error ) => console.log( error ) );

}

function removeCard( id ) {

    fetch( `${window.location.href}api/removeCard.php` , {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify( {
            ID      : id,
        } )
    } )
        .then( ( data ) => {
            document.getElementById( id ).remove();
        } )
        .catch( ( error ) => console.log( error ) );

}