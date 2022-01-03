<?php

    require_once( "../config.php" );
    require_once( "../Class/BDD.php" );

    // —— Loads all the parameters of my post method.
    $content = trim( file_get_contents( "php://input" ) );

    // —— Transforms the character string into a JSON object
    $data = json_decode( $content, true );

    // —— Check if all the parameters are set
    if ( !isset( $data[ "ID" ] ) ) {
        echo json_encode( array( "error" => "Missing parameters" ) );
        exit;
    }

    try {
        // —— Connect to the database
        $db = new BDD( $config[ "db_host" ], $config[ "db_name" ], $config[ "db_user" ], $config[ "db_pass" ] );

        $user = $db->removeCard( $data[ "ID" ] );

    } catch ( Exception $e ) {
        echo json_encode( array( "error" => $e->getMessage() ) );
        exit;
    }

?>