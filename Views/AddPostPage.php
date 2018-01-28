<?php

?>

<html>
    <head>
        <title>Page Admin</title>
    </head>
    <body>
        <form method="post" action="index.php?action=addBlogPost">
            <label for='author'>Auteur : </label> <input  type="text" name="author" id="author" /> </br>
            <label for="title">Titre : </label> <input type="text" name="title" id="title" /> </br>
            <label for="content">Texte du BlogPost : </label> <textarea name="content" id="content" rows="10" cols="50" required></textarea>
            <button type="submit" value="Enregistrer">Enregistrer</button>
        </form>
    </body>
</html>
