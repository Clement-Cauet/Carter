const cardContent       = document.getElementById( "cardContent" );
let editMode = false;
let currentCard = [ false, 0, 0, "" ];

// Websocket connection to the reader
const socket = new WebSocket( "ws://192.168.64.103:4457" );

// Websocket status : message
socket.addEventListener( "message", ( event ) => {

    // Get message parts
    const [ hasRead, cardType, cardSerial, data ] = event.data.split( "," );
    currentCard = [ hasRead, cardType, cardSerial, data ];

    if ( hasRead === "false" ) {
        cardContent.innerHTML = `No card detected, please put your card on the reader`;
        editMode = false;
    } else if ( editMode === false ) {
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
        .then( ( response ) => response.json( ) )
        .then( ( data ) => {
            console.log( data );
            editMode = false;
            edit( );
        } )
        .catch( ( error ) => console.log( error ) );

}
