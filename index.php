<!DOCTYPE html>
<html lang="en">
    <?php
    session_start();
        include ("session.php");
    ?>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="src/css/style.css" rel="stylesheet">
    <title>Index</title>
</head>

<body>
    <div class="contenu">

        <div class="Data-bdd">
            <?php $user->selectUser(); ?>
        </div>

        <?php
            $user->insertUser();
        ?>
    </div>

</body>

</html>