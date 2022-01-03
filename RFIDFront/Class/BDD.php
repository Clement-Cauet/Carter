<?php

    class BDD {

        // Define the default database parameters
        private $host = "localhost";
        private $dbname = "bdd";
        private $user = "root";
        private $password = "";

        public function __construct( $host = "localhost", $dbname = "bdd", $user = "root", $password = "" ) {

            // Create a connection to the database
            $this->host = $host;
            $this->dbname = $dbname;
            $this->user = $user;
            $this->password = $password;
            $this->connect();

        }

        // Connect to the database
        private function connect() {

            // Create a connection to the database
            $this->db = new PDO( "mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->password );

        }

        public function getCard( $id ) {

            // Prepare the SQL query
            $query = $this->db->prepare( "SELECT * FROM cards WHERE ID = :id" );

            // Bind the parameters
            $query->bindParam( ":id", $id );

            // Execute the query
            $query->execute();

            // Return the result
            return $query->fetch();

        }

        public function getCards() {

            // Prepare the SQL query
            $query = $this->db->prepare( "SELECT * FROM cards" );

            // Execute the query
            $query->execute();

            // Return the result
            return $query->fetchAll();

        }

        public function getCardsByType( $type ) {

            // Prepare the SQL query
            $query = $this->db->prepare( "SELECT * FROM cards WHERE CardType = :type" );

            // Bind the parameters
            $query->bindParam( ":type", $type );

            // Execute the query
            $query->execute();

            // Return the result
            return $query->fetchAll();

        }

        public function saveCardContent( $id, $content ) {

            // Prepare the SQL query
            $query = $this->db->prepare( "UPDATE cards SET Data = :Data WHERE ID = :id" );

            // Bind the parameters
            $query->bindParam( ":id", $id );
            $query->bindParam( ":Data", $content );

            // Execute the query
            $query->execute();

        }

        public function saveCard( $id, $type, $content ) {

            // Prepare the SQL query
            $query = $this->db->prepare( "INSERT INTO cards ( ID, CardType, Data ) VALUES ( :id, :CardType, :Data )" );

            // Bind the parameters
            $query->bindParam( ":id", $id );
            $query->bindParam( ":CardType", $type );
            $query->bindParam( ":Data", $content );

            // Execute the query
            $query->execute();

        }

    }



?>