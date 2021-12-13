<?php
    class user{

        // - Propriétés
        private $_id;
        private $_carteid;
        private $_nom;
        private $_prenom;
        private $_admin;
        private $_bdd;
        private $_req;

        // - Méthodes
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        // Initialisation des variables
        public function setUser($id, $carteid, $nom, $prenom, $admin){
            $this->_id = $id;
            $this->_carteid = $carteid;
            $this->_nom = $nom;
            $this->_prenom = $prenom;
            $this->_admin = $admin;
        }

        // Permet de récupérer les données de l'utilisateur en BDD
        public function setUserByID($id){
            $req = "SELECT * FROM `users` WHERE `user_id`='".$id."'";
            $Result = $this->_bdd->query($req);
            while($tab = $Result->fetch()){
                $this->setUser($tab["user_id"],$tab["carte_id"], $tab["nom"], $tab["prenom"], $tab["admin"]);
            }
        }

        // Retour de la variable $_ID 
        public function getID(){
            return $this->_id;
        }

        public function getPrenom(){
            return $this->_prenom;
        }

        // Retour de la variable $_user
        public function getnom(){
            return $this->_nom;
        }

        public function getcarteID(){
            return $this->_carteid;
        }

        // Retour de la variable $_admin
        public function getAdmin(){
            return $this->_admin;
        }
       
        //Fonction inscrire et insérer les données dans la bdd
        public function inscription(){
            $afficheForm = true;
            /*$error1 = false;
            $error2 = false;*/
            $_SESSION["Connected"] = false;
            if(isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["carte_id"])){
                /*if($_POST["prenom"] == $_POST["conf-mdp"]){*/
                    $this->_req = "SELECT COUNT(*) FROM `users` WHERE `carte_id`='".$_POST['carte_id']."' AND `nom`='".$_POST['nom']."' AND `prenom` = '".$_POST['prenom']."'";
                    $Result = $this->_bdd->query($this->_req);
                    $nbr = $Result->fetch();
                    if($nbr['COUNT(*)'] == 0){
                        $carteid = $_POST['carte_id'];$nom = $_POST['nom']; $prenom = $_POST['prenom']; $admin = 0;
                        $this->_req = "INSERT INTO `users`(`carte_id`,`nom`, `prenom`, `admin`) VALUES('$carteid', '$nom', '$prenom', '$admin')";
                        $Result = $this->_bdd->query($this->_req);
                        $this->_req = "SELECT * FROM `users` WHERE `nom`='".$_POST['nom']."' AND `prenom` = '".$_POST['prenom']."'";
                        $Result = $this->_bdd->query($this->_req);
                        if($tab = $Result->fetch()){
                            $this->setUserByID($tab["id"]);
                            $_SESSION["id"] = $tab["id"];
                            $_SESSION["Connected"] = true;
                            $afficheForm = false;
                            header("location: index.php");
                        }
                    }else{
                        $error2 = true;
                    }
                /*}else{
                    $error1 = true;
                }*/
            }else{
                $afficheForm = true;
            }

            if($afficheForm == true){
                ?>
                    <!--<head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Inscription</title>
                        <link rel="icon" type="image/png" href="src/img/icon.png">
                        <link rel='stylesheet' type='text/css' href='src/css/style.css'>
                        <link rel='stylesheet' type='text/css' href='src/css/formulaire.css'>
                    </head>
                    <body>-->
                        <div class="back">
                            <div class="form-user">
                                <h2>Inscription</h2>
                                <form method="post">
                                    <?php
                                        /*if($error1 == true){
                                            ?><div class="erreur">Veuillez confirmer le même mot de passe</div><?php
                                        }*/
                                        if($error2 == true){
                                            ?><div class="erreur">Compte déjà existant</div><?php
                                        }
                                    ?>
                                    <div class="user">
                                        <input type="text" id="login" name="login" class="login-input" placeholder="Carte_ID" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="mdp">
                                        <input type="text" id="mdp" name="mdp" class="mdp-input" placeholder="Nom" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="conf-mdp">
                                        <input type="text" id="conf-mdp" name="conf-mdp" class="conf-mdp-input" placeholder="Prenom" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="submit-button">
                                        <input type="submit" class="button" value="S'inscrire"></input>
                                    </div>
                                    <!--<p><a href="index.php">Déjà un compte</a></p>-->
                                </form>
                            </div>
                        </div>
                    <!--</body>-->
                <?php
            }
        }

        //Fonction qui permet de se connecter
        /*public function connexion(){
            $afficheForm = true;
            $error = false;
            $_SESSION["Connected"] = false;
            if(isset($_POST["nom"]) && isset($_POST["prenom"])){
                $this->_req = "SELECT * FROM `users` WHERE `nom`='".$_POST['nom']."' AND `prenom` = '".$_POST['prenom']."'";
                $Result = $this->_bdd->query($this->_req);
                if($tab = $Result->fetch()){
                    $this->setUserByID($tab["id"]);
                    $_SESSION["id"] = $tab["id"];
                    $_SESSION["Connected"] = true;
                    $afficheForm = false;
                }else{
                    $afficheForm = true;
                    $error = true;
                }
            }else{
                $afficheForm = true;
            }

            if($afficheForm == true){
                ?>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Connexion</title>
                        <link rel="icon" type="image/png" href="src/img/icon.png">
                        <link rel='stylesheet' type='text/css' href='src/css/style.css'>
                        <link rel='stylesheet' type='text/css' href='src/css/formulaire.css'>
                    </head>
                    <body>
                        <div class="back">
                            <div class="form-user">
                                <h2>Connexion</h2>
                                <form method="post">
                                    <?php
                                        if($error == true){
                                            ?><div class="erreur">login ou mot de passe invalide</div><?php
                                        }
                                    ?>
                                    <div class="user">
                                        <input type="text" id="login" name="login" class="login-input" placeholder="Votre login" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="mdp">
                                        <input type="password" id="mdp" name="mdp" class="mdp-input" placeholder="Votre mot de passe" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="submit-button">
                                        <input type="submit" class="button" value="Ouverture de session"></input>
                                    </div>
                                    <p><a href="inscription.php">Register</a></p>
                                </form>
                            </div>
                        </div>
                    </body>
                <?php
            } 
        }
*/
        //Fonction se deconnecter de la session
        /*public function deconnexion(){
            session_unset();
            session_destroy();
            unset($_POST);
            echo '<meta http-equiv="refresh" content="0">';
        }*/

        // Affiche les données (id, login, admin) de l'utilisateur
        public function selectUser(){
            $this->_req = "SELECT `user_id`, `carte_id`, `nom`, `prenom` FROM `users` WHERE 1";
            $Result = $this->_bdd->query($this->_req);
            ?>
            <div class="user">
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Nom</td>
                            <td>Admin</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($tab = $Result->fetch()){
                                ?>
                                    <tr id="<?= $tab['user_id'] ?>">
                                        <td>
                                            <div>
                                                <?//= $tab['user_id'] ?><?= $tab['user_id'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <?//= $tab['user_id'] ?><?= $tab['nom'] ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                
                                                    <?php
                                                        if($tab['admin'] == 1){
                                                            echo "Oui";
                                                        }else{
                                                            echo "Non";
                                                        }
                                                    ?>
                                                
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
                        </div>
            <?php
        }

        // Permet d'ajouter un utilisateur en BDD
        public function insertUser(){
            ?>
            <div class="insertuser">
                <form method="post">
                    <div class="account">
                        <div class="input">
                            <input type="text" id="carte_id" name="carte_id" placeholder="ID carte" required>
                        </div>
                        <div class="input">
                            <input type="text" id="prenom" name="prenom" class="form-input" placeholder="nom" required>
                        </div>
                        <div class="input">
                            <input type="text" id="nom" name="nom" class="form-input" placeholder="prenom" required>
                        </div>
                    </div>
                    <div class="admin">
                        <select name="admin" class="form-input" required>
                        <option value="" disable select hidden>Admin</option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="submit-button">
                        <input type="submit" name="submit" class="button" value="Ajouter">
                    </div>
                </form>
        </div>
            <?php
            if(isset($_POST['submit'])){
                $carteid = $_POST['carte_id']; $prenom = $_POST['prenom']; $nom = $_POST['nom']; $admin = $_POST['admin'];
                $this->_req = "INSERT INTO `users`(`carte_id`,`prenom`, `nom`, `admin`) VALUES('$carteid','$prenom', '$nom', '$admin')";
                $this->_bdd->query($this->_req);
                unset($_POST);
                echo '<meta http-equiv="refresh" content="0">';
            }
        }

        // Formulaire pour la modification et la suppression d'un utilisateur
        public function formUser(){

            $this->_req = "SELECT `carte_id`, `nom`, `prenom`, `admin` FROM `users`";
            $Result = $this->_bdd->query($this->_req);
            if ( $tab = $Result->fetch() ){
                ?>
                    <div class="form-user">
                        <form method="post">
                            <div class="account">
                                <input type="text" name="carte_id" value="<?= $tab['carte_id'] ?>" required>
                                <label>Nom : </label>
                                <input type="text" class="form-input" id="login" name="nom" value="<?= $tab['nom'] ?>" required>
                                <label>Prenom : </label>
                                <input type="text" class="form-input" id="mdp" name="prenom" value="<?= $tab['prenom'] ?>" required>
                            </div>
                            <div class="admin">
                                <label>Administrateur : </label>
                                <select class="form-input" id="admin" name="admin" required>
                                    <?php
                                        if($tab['admin'] == 1){
                                            ?>
                                                <option value="<?= $tab['admin'] ?>">Oui</option>
                                                <option value="0">Non</option>
                                            <?php
                                        }
                                        else{
                                            ?>
                                                <option value="<?= $tab['admin'] ?>">Non</option>
                                                <option value="1">Oui</option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="submit-button">
                                <input type="submit" id="save" name="save" class="button" value="Enregistrer">
                                <input type="submit" id="suppr_confirm" name="suppr_confirm" class="button" value="Supprimer définitivement">
                                <input type="button" id="suppr" name="suppr" class="button" value="Supprimer">
                                <input type="button" id="cancel" name="cancel" class="button" value="Annuler">
                            </div>
                        </form>
                    </div>
                <?php
            }
            else{
                echo "No user found";
            }
        }
    }
?>