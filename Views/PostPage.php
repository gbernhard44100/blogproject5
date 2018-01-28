<?php

?>

<DOCTYPE HTML>
    <html>
        <head>
            <title>Bernhard Design: Ma Blog List</title>
        </head>
        <body>
            <?php 
            $donnees = $blogPost->fetch();
                echo ''
                . '<H1>'.$donnees['title'].'</H1> </br>'
                . '<H4>Dernière mise à jour le '.$donnees['update_date'].' par '.$donnees['author'].'</H4> </br>'
                . '<p>'.$donnees['content'].'</p> </br></br>';
            ?>
        </body>
    </HTML> 