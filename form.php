<?php

// Je vérifie si le formulaire est soumis comme d'habitude
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Securité en php
    // chemin vers un dossier sur le serveur qui va recevoir les fichiers uploadés (attention ce dossier doit être accessible en écriture)
    $uploadDir = '/uploads';
    // le nom de fichier sur le serveur est ici généré à partir du nom de fichier sur le poste du client (mais d'autre stratégies de nommage sont possibles)
    $uploadFile = $uploadDir . basename($_FILES['image_file']['name']);
    // Je récupère l'extension du fichier
    $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
    // Les extensions autorisées
    $authorizedExtensions = ['jpg', 'jpeg', 'png'];
    // Le poids max géré par PHP par défaut est de 2M
    $maxFileSize = 2000000;

    // Je sécurise et effectue mes tests

    /****** Si l'extension est autorisée *************/
    if (!in_array($extension, $authorizedExtensions)) {
        $errors[] =
            'Veuillez sélectionner une image de type Jpg ou Jpeg ou Png !';
    }

    /****** On vérifie si l'image existe et si le poids est autorisé en octets *************/
    if (
        file_exists($_FILES['image_file']['tmp_name']) &&
        filesize($_FILES['image_file']['tmp_name']) > $maxFileSize
    ) {
        $errors[] = 'Votre fichier doit faire moins de 2M !';
    }

    /****** Si je n'ai pas d"erreur alors j'upload *************/

    if (empty($errors)) {
        $fileExt = explode('.', $uploadFile);

        $fileActualExt = strtolower(end($fileExt));

        // Sending file to the server

        // Creating unique name + its current extension
        $fileNameNew = uniqid('homer', true) . '.' . $fileActualExt;
        $fileDestination = 'uploads/' . $fileNameNew;

        move_uploaded_file($_FILES['image_file']['tmp_name'], $fileDestination);
    }

    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $firstname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $firstname =
        isset($_POST['age']) && is_numeric($_POST['age'])
            ? trim($_POST['age'])
            : '';

    if ($firstname < 5 || $lastname < 5) {
        $errors[] =
            'Your lastname and your firstname must have five characters or more';
    }
    if ($age < 1 || $age > 150) {
        $errors[] =
            'Your age cannot be negative or cannot be over 150 years old';
    }
} ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <!-- CSS only -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
<!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
</head>
<body>
    <header>
    </header>
    <main>
        <?php if (!empty($errors)) {
            foreach ($errors as $error) { ?> <p><?php echo $error; ?></p><?php }
        } ?>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="file" name="image_file" id="image_file" />
            <input type="text" name="firstname" id="firstname"  maxlength="100" minlength="5" required/>
            <input type="text" name="lastname" id="lastname" maxlength="100" minlength="5" required/>
            <input type="number" name="age" id="age" required/>

            <button name="send">Send</button>
        </form>
         
        <div class="card" style="width: 18rem;">
            <img src="/uploads/homer6361272275d874.93164566.jpeg" class="card-img-top" alt="...">
            <div class="card-body">
                <h5 class="card-title"><?php echo $_POST['firstname'] ?></h5>
                <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $_POST['lastname'] ?></h5>
            </div>
            <div class="card-body">
                <h5 class="card-title"><?php echo $_POST['age'] ?></h5>
            </div>
        </div>

    </main>
    <footer>
    </footer>
</body>
</html>