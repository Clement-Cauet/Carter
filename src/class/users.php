<?php
    class user{

        // - Propriétés
        private $_id;
        private $_login;
        private $_password;
        private $_admin;
        private $_bdd;
        private $_req;

        // - Méthodes
        public function __construct($bdd){
            $this->_bdd = $bdd;
        }

        // Initialisation des variables
        public function setUser($id, $login, $password, $admin){
            $this->_id = $id;
            $this->_login = $login;
            $this->_password = $password;
            $this->_admin = $admin;
        }

        // Permet de récupérer les données de l'utilisateur en BDD
        public function setUserByID($id){
            $req = "SELECT * FROM `users` WHERE `id`='".$id."'";
            $Result = $this->_bdd->query($req);
            while($tab = $Result->fetch()){
                $this->setUser($tab["id"], $tab["login"], $tab["password"], $tab["admin"]);
            }
        }

        // Retour de la variable $_ID 
        public function getID(){
            return $this->_id;
        }

        // Retour de la variable $_user
        public function getLogin(){
            return $this->_login;
        }

        // Retour de la variable $_admin
        public function getAdmin(){
            return $this->_admin;
        }
       
        //Fonction inscrire et insérer les données dans la bdd
        public function inscription(){
            $afficheForm = true;
            $error1 = false;
            $error2 = false;
            $_SESSION["Connected"] = false;
            if(isset($_POST["login"]) && isset($_POST["mdp"]) && isset($_POST["conf-mdp"])){
                if($_POST["mdp"] == $_POST["conf-mdp"]){
                    $this->_req = "SELECT COUNT(*) FROM `users` WHERE `login`='".$_POST['login']."' AND `password` = '".$_POST['mdp']."'";
                    $Result = $this->_bdd->query($this->_req);
                    $nbr = $Result->fetch();
                    if($nbr['COUNT(*)'] == 0){
                        $login = $_POST['login']; $mdp = $_POST['mdp']; $admin = 0;
                        $this->_req = "INSERT INTO `users`(`login`, `password`, `admin`) VALUES('$login', '$mdp', '$admin')";
                        $Result = $this->_bdd->query($this->_req);
                        $this->_req = "SELECT * FROM `users` WHERE `login`='".$_POST['login']."' AND `password` = '".$_POST['mdp']."'";
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
                }else{
                    $error1 = true;
                }
            }else{
                $afficheForm = true;
            }

            if($afficheForm == true){
                ?>
                    <head>
                        <meta charset="UTF-8">
                        <meta name="viewport" content="width=device-width, initial-scale=1.0">
                        <title>Inscription</title>
                        <link rel="icon" type="image/png" href="src/img/icon.png">
                        <link rel='stylesheet' type='text/css' href='src/css/style.css'>
                        <link rel='stylesheet' type='text/css' href='src/css/formulaire.css'>
                    </head>
                    <body>
                        <div class="back">
                            <div class="form-user">
                                <h2>Inscription</h2>
                                <form method="post">
                                    <?php
                                        if($error1 == true){
                                            ?><div class="erreur">Veuillez confirmer le même mot de passe</div><?php
                                        }
                                        if($error2 == true){
                                            ?><div class="erreur">Compte déjà existant</div><?php
                                        }
                                    ?>
                                    <div class="user">
                                        <input type="text" id="login" name="login" class="login-input" placeholder="Votre login" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="mdp">
                                        <input type="password" id="mdp" name="mdp" class="mdp-input" placeholder="Votre mot de passe" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="conf-mdp">
                                        <input type="password" id="conf-mdp" name="conf-mdp" class="conf-mdp-input" placeholder="Confirmer votre mot de passe" autocomplete="off" autocapitalize="off" required></input>
                                    </div>
                                    <div class="submit-button">
                                        <input type="submit" class="button" value="S'inscrire"></input>
                                    </div>
                                    <p><a href="index.php">Déjà un compte</a></p>
                                </form>
                            </div>
                        </div>
                    </body>
                <?php
            }
        }

        //Fonction qui permet de se connecter
        public function connexion(){
            $afficheForm = true;
            $error = false;
            $_SESSION["Connected"] = false;
            if(isset($_POST["login"]) && isset($_POST["mdp"])){
                $this->_req = "SELECT * FROM `users` WHERE `login`='".$_POST['login']."' AND `password` = '".$_POST['mdp']."'";
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

        //Fonction se deconnecter de la session
        public function deconnexion(){
            session_unset();
            session_destroy();
            unset($_POST);
            echo '<meta http-equiv="refresh" content="0">';
        }

        // Affiche les données (id, login, admin) de l'utilisateur
        public function selectUser(){
            $this->_req = "SELECT `id`, `login`, `admin` FROM `users` WHERE 1";
            $Result = $this->_bdd->query($this->_req);
            ?>
                <table>
                    <thead>
                        <tr>
                            <td>ID</td>
                            <td>Login</td>
                            <td>Admin</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            while($tab = $Result->fetch()){
                                ?>
                                    <tr id="<?= $tab['id'] ?>">
                                        <td>
                                            <div>
                                                <a href="./src/edit/editUser.php?user=<?= $tab['id'] ?>"><?= $tab['id'] ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="./src/edit/editUser.php?user=<?= $tab['id'] ?>"><?= $tab['login'] ?></a>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <a href="./src/edit/editUser.php?user=<?= $tab['id'] ?>">
                                                    <?php
                                                        if($tab['admin'] == 0){
                                                            echo "Non";
                                                        }else{
                                                            echo "Oui";
                                                        }
                                                    ?>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php
                            }
                        ?>
                    </tbody>
                </table>
            <?php
        }

        // Permet d'ajouter un utilisateur en BDD
        public function insertUser(){
            ?>
                <form method="post">
                    <div class="account">
                        <div class="input">
                            <input type="text" id="login" name="login" class="form-input" placeholder="Login" required>
                        </div>
                        <div class="input">
                            <input type="text" id="password" name="password" class="form-input" placeholder="Mot de passe" required>
                        </div>
                    </div>
                    <div class="admin">
                        <label>Administrateur : </label>
                        <select name="admin" class="form-input" required>
                            <option value=""></option>
                            <option value="1">Oui</option>
                            <option value="0">Non</option>
                        </select>
                    </div>
                    <div class="submit-button">
                        <input type="submit" name="submit" class="button" value="Ajouter">
                    </div>
                </form>
            <?php
            if(isset($_POST['submit'])){
                $login = $_POST['login']; $password = $_POST['password']; $admin = $_POST['admin'];
                $this->_req = "INSERT INTO `users`(`login`, `password`, `admin`) VALUES('$login', '$password', '$admin')";
                $this->_bdd->query($this->_req);
                unset($_POST);
                echo '<meta http-equiv="refresh" content="0">';
            }
        }

        // Formulaire pour la modification et la suppression d'un utilisateur
        public function formUser($id){

            $this->_req = "SELECT `login`, `password`, `admin` FROM `users` WHERE `id` = '".$id."'";
            $Result = $this->_bdd->query($this->_req);
            if ( $tab = $Result->fetch() ){
                ?>
                    <div class="form-user">
                        <form method="post">
                            <div class="account">
                                <label>Login : </label>
                                <input type="text" class="form-input" id="login" name="login" value="<?= $tab['login'] ?>" required>
                                <label>Mot de passe : </label>
                                <input type="text" class="form-input" id="mdp" name="mdp" value="<?= $tab['password'] ?>" required>
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