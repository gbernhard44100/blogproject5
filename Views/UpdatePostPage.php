<?php

?>

<html>
    <head>
        <title>Page Admin</title>
    </head>
    <body>
        <form method="post" action="index.php?action=updateBlogPost">
            <label for='author'>Auteur : </label> <input  type="text" name="author" id="author" value="<?= $data_to_update['author'] ?>"/> </br>
            <label for="title">Titre : </label> <input type="text" name="title" id="title" value="<?= $data_to_update['title'] ?>"/> </br>
            <label for="content">Texte du BlogPost : </label> <textarea name="content" id="content" rows="10" cols="50" /><?= $data_to_update['content'] ?></textarea>
            <input type="hidden" name="id" id="id" value="<?= $data_to_update['id'] ?>"/>
            <button type="submit" value="Enregistrer">Enregistrer</button>
        </form>
    </body>
</html>