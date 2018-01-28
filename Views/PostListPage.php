<?php

?>

<DOCTYPE HTML>
    <html>
        <head>
            <title>Bernhard Design: Ma Blog List</title>
        </head>
        <body>
            <?php 
            while($data = $allBlogPosts->fetch()){
                echo ''
                . '<H1>'.$data['title'].'</H1><a href="index.php?action=requestUpdateBlogPost&amp;id='.$data['id'].'">Modifier</a></br>'
                . '<H4>Dernière mise à jour le '.$data['update_date'].' par '.$data['author'].'</H4> </br>'
                . '<p>'.substr($data['content'],0,50).' <a href="index.php?action=showBlogPost&amp;id='.$data['id'].'">Lire la suite</a></p> </br></br>';
            }
            ?>
        </body>
    </HTML>    
    