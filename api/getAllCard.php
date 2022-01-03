<?php

    require_once( "../config.php" );
    require_once( "../Class/BDD.php" );

    try {
        // —— Connect to the database
        $db = new BDD( $config[ "db_host" ], $config[ "db_name" ], $config[ "db_user" ], $config[ "db_pass" ] );

        echo json_encode( $db->getCards() );

    } catch ( Exception $e ) {
        echo json_encode( array( "error" => $e->getMessage() ) );
        exit;
    }

?>