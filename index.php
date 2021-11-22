<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="src/css/style.css" rel="stylesheet">
    <title>Index</title>
</head>

<body>

    <div class="EditCarte">
        <form action="" method="">
            <label>Editer la carte</label>
            <textarea name="zoneEditCarte"></textarea>
            <div>
                <input type="button" value="Envoyer">
            </div>
        </form>
    </div>

    <div class="Inscription">
        <form action="" method="">
            <input type="text" name="nom" placeholder="Nom">
            <input type="text" name="prenom" placeholder="Prenom">
            <input type="button" value="S'inscrire">
        </form>
    </div>

    <div class="Admin-insert">
        <form action="" method="post">
            <input type="text" name="nom" placeholder="Nom">
            <input type="text" name="prenom" placeholder="Prenom">
            <select name="admin">
                <option value="" disable select hidden>Admin</option>
                <option value="Yes">Oui</option>
                <option value="No">Non</option>
            </select>
            <input type="button" value="Ajouter">
        </form>
    </div>

    <div class="Admin-update">
        <form action="" method="post">
            <input type="text" name="nom" placeholder="Nom">
            <input type="text" name="prenom" placeholder="Prenom">
            <select name="admin">
                <option value="" disable select hidden>Admin</option>
                <option value="Yes">Oui</option>
                <option value="No">Non</option>
            </select>
            <input type="button" value="Modifier">
        </form>
    </div>

    <div class="Admin-delete">
        <form action="" method="">
            <input type="button" value="Supprimer">
        </form>
    </div>

    <div class="Data-bdd">

    </div>



</body>

</html>